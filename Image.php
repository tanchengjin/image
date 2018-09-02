<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/8/31
 * Time: 20:39
 */

header("Content-type:text/html;charset=utf8");
class Image
{
    //图片路径
    private $imagePath=null;
    //水印图片路径
    private $watermarkImagePath=null;
    //图片处理后,图片的前缀名
    private $prefix='x_';
    //文件保存路径,默认当前路径下
    private $savePath='./';

    //图片资源
    private $img_res;
    //处理后的图片资源
    private $img_new;

    //水印图片资源
    private $watermark_res;
    //最后一次所操作水印图片的类型
    private $watermark_type;
    //最后一次所操作水印图片的宽度
    private $watermark_w;
    //最后一次所操作水印图片的高度
    private $watermark_h;

    //最后一次所操作图片的类型
    private $img_type;
    //最后一次所操作图片的宽度
    private $img_w;
    //最后一次所操作图片的高度
    private $img_h;
    //存储相关错误信息
    private $error;

    /**
     * 设置图片路径以及在内存中创建图片资源
     * @param null $imagePath
     * @return $this;
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
        list($this->img_w,$this->img_h,$this->img_type)=$this->getImageInfo($this->imagePath);
        $this->img_res=$this->imagecreate($imagePath,$this->img_type);
        return $this;
    }

    /**
     * 设置水印图片路径以及在内存中创建水印图片资源
     * @param null $watermarkImagePath
     * @return $this;
     */
    public function setWatermarkImagePath($watermarkImagePath)
    {
        $this->watermarkImagePath = $watermarkImagePath;
        list($this->watermark_w,$this->watermark_h,$this->watermark_type)=$this->getImageInfo($this->watermarkImagePath);
        $this->watermark_res=$this->imagecreate($this->watermarkImagePath,$this->watermark_type);
        return $this;
    }
    /**
     * 获取最后一次所操作图片的宽度
     * @return mixed
     */
    public function getImgW()
    {
        return $this->img_w;
    }

    /**
     * 获取最后一次所操作图片的高度
     * @return mixed
     */
    public function getImgH()
    {
        return $this->img_h;
    }

    /**
     * 获取前缀
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * 设置保存文件时的前缀,如果为null则不使用前置
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * 获得保存文件的路径
     * @return string
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * 设置保存文件的路径默认为当前路径
     * @param string $savePath
     */
    public function setSavePath($savePath)
    {
        $this->savePath = $savePath;
    }


    /**
     * 图片水印
     * @param null $imagePath String 所要被添加水印的图片路径
     * @param null $watermarkImagePath String 水印图片路径
     * @param $position int|string 1左上角,2顶部居中,3右上角,4右侧居中,5右下角,6底部居中,7左下角,8左侧居中,9垂直居中
     * @return $this;
     */
    public function watermarkImage($imagePath=null,$watermarkImagePath=null,$position=5){
        if(!is_null($watermarkImagePath)){
            $this->setWatermarkImagePath($watermarkImagePath);
        }
        if(!is_null($imagePath)){
            $this->setImagePath($imagePath);
        }
        //获取图片xy轴
        $xy=$this->getImageXY($this->img_w,$this->img_h,$this->watermark_w,$this->watermark_h,$position);
        imagecopy($this->img_res,$this->watermark_res,$xy[0],$xy[1],0,0,$this->watermark_w,$this->watermark_h);
        $this->img_new=$this->img_res;
        return $this;
    }

    /**
     * 字符串水印
     * @param $imagePath String 需要被添加水印的图片路径
     * @param $content String 水印内容
     * @param $fontSize int 字体大小
     * @param int $position int|String 水印位置
     * @param String $fontColor string 字体颜色默认为黑色
     * @return $this;
     */
    public function watermarkString($imagePath=null,$content,$position=5,$fontSize=5,$fontColor='black'){
        if(!is_null($imagePath)){
            $this->setImagePath($imagePath);
        }
        list($fontX,$fontY)=$this->getImageXY($this->img_w,$this->img_h,imagefontwidth($fontSize)*strlen($content),imagefontheight($fontSize),$position);
        list($red,$green,$blue)=$this->getFontColor($fontColor);
        $fontColor=imagecolorallocate($this->img_res,$red,$green,$blue);
        imagestring($this->img_res,5,$fontX,$fontY,$content,$fontColor);
        $this->img_new=$this->img_res;
        return $this;
    }

    /**
     * 以图片X轴水平翻转图片
     * @param null $imagePath String 图片路径
     * @return $this;
     */
    public function flip_x($imagePath=null){
        if(!is_null($imagePath)){
            $this->setImagePath($imagePath);
            list($this->img_w,$this->img_h,$this->img_type)=$this->getImageInfo($imagePath);
        }
        //如果是链式操作
        if(is_resource($this->img_new)){
            $this->img_res=$this->img_new;
        }
        $this->img_new=imagecreatetruecolor($this->img_w,$this->img_h);
        for($x=0;$x<$this->img_w;$x++){
            imagecopy($this->img_new,$this->img_res,$this->img_w-$x-1,0,$x,0,1,$this->img_h);
        }
        return $this;
    }

    public function cut($imagePath=null,$x,$y,$width,$height){
        if(!is_null($imagePath)){
            $this->setImagePath($imagePath);
        }
        $this->img_new=imagecreatetruecolor($width,$height);
        imagecopyresampled($this->img_new,$this->img_res,0,0,$x,$y,$width,$height,$width,$height);
        return $this;
    }
    /**
     * 以图片Y轴进行水平翻转
     * @param null $imagePath String 图片路径
     * @return $this
     */
    public function flip_y($imagePath=null){
        if(!is_null($imagePath)){
            $this->setImagePath($imagePath);
            list($this->img_w,$this->img_h,$this->img_type)=$this->getImageInfo($imagePath);
        }
        //如果是链式操作
        if(is_resource($this->img_new)){
            $this->img_res=$this->img_new;
        }
        $this->img_new=imagecreatetruecolor($this->img_w,$this->img_h);
        for($y=0;$y<$this->img_h;$y++){
            imagecopy($this->img_new,$this->img_res,0,$this->img_h-$y-1,0,$y,$this->img_h,1);
        }
        return $this;

    }

    /**
     * 旋转图片
     * @param $angle int 所要旋转图片的角度
     * @param Null $imagepath String 所要旋转的图片路径
     * @return $this|bool
     */
    public function rotate($angle,$imagepath=null){
        if($imagepath !== null){
            $this->imagecreate($imagepath);
        }
        if(is_null($this->img_res)){
            $this->error.="rotate not found image source";
            return false;
        }else{
            $this->img_new=imagerotate($this->img_res,$angle,0);
        }
        return $this;
    }

    /**
     * 将图片压缩成缩略图
     * @param null $imagePath 要压缩图片路径
     * @param int $width 缩略图宽度
     * @param int $height 缩略图高度
     * @return $this
     */
    public function thumb($imagePath=null,$width=200,$height=200){
        if(!is_null($imagePath)){
            $this->setImagePath($imagePath);
        }
        //创建缩略后的图片资源
        $this->img_new=imagecreatetruecolor($width,$height);

        imagecopyresampled($this->img_new,$this->img_res,0,0,0,0,$width,$height,$this->img_w,$this->img_h);
        return $this;
    }

    /**
     * 在浏览器中输出图片
     * @param bool $destroy 如果为true则销毁所有图片资源,如果为false则不进行销毁
     */
    public function image_print($destroy=true){
        $display="image{$this->img_type}";
        header("Content-type:{$this->img_type}");
        $display($this->img_new);
        if($destroy === true){
            $this->image_destroy();
        }
    }

    /**
     * 图片保存
     * @param $fileName String 文件名
     * @param null $savePath String 文件保存路径
     * @param null $prefix 文件名前缀
     */
    public function image_save($fileName,$savePath=null,$prefix=null){
        if(!is_null($savePath)){
            $this->savePath=$savePath;
        }
        if(!is_null($prefix)){
            $this->prefix=$prefix;
        }

        if(is_null($prefix)){
            $fileNames=$fileName.".{$this->img_type}";
        }else{
            $fileNames=$prefix.$fileName.".{$this->img_type}";
        }
        $save="image{$this->img_type}";
        $save($this->img_new,$fileNames);
        $this->image_destroy();
    }

    /**
     * 创建图片资源
     * @param $path String 图片路径
     * @param $type String 图片类型
     * @return mixed resource
     */
    private function imagecreate($path,$type){
        $spliceString="imagecreatefrom{$type}";
        return $spliceString($path);
    }

    /**
     * 获取图片宽度,高度,类型
     * @param $imagePath
     * @param false $dot boolean 是否获得文件类型前面的小圆点
     * @return mixed
     */
    private function getImageInfo($imagePath,$dot=false){
        list($imageInfo[0],$imageInfo[1],$imageInfo[2])=getimagesize($imagePath);
        $imageInfo[2]=image_type_to_extension($imageInfo[2],$dot);
        return $imageInfo;
    }

    /**
     * 获取图片的xy坐标
     * @param $image_w int 图片的宽度
     * @param $image_h int 图片的高度
     * @param $watermark_w int 水印图片的宽度
     * @param $watermark_h int 水印图片的高度
     * @param $position int|string 0左上角,1顶部居中,2右上角,3右侧居中,4右下角,5底部居中,6左下角,7左侧居中,8垂直居中
     * @return mixed Array 返回图片的xy坐标
     */
    private function getImageXY($image_w,$image_h,$watermark_w,$watermark_h,$position){
        //左上角坐标
        if($position === 1 || $position == 'leftTop'){
            $xy[0]=0;
            $xy[1]=0;
        }elseif($position == 2){
         //顶部居中坐标
            $xy[0]=($image_w-$watermark_w)/2;
            $xy[1]=0;
        }elseif($position == 3 || $position == 'rightTop'){
            //右上角坐标
            $xy[0]=($image_w-$watermark_w);
            $xy[1]=0;
        }elseif($position == 4 || $position == 'rightCenter'){
            //右侧坐标
            $xy[0]=$image_w-$watermark_w;
            $xy[1]=($image_h-$watermark_h)/2;
        }elseif($position == 5 || $position == 'rightBottom'){
            //右下角坐标
            $xy[0]=$image_w-$watermark_w;
            $xy[1]=$image_h-$watermark_h;
        }elseif($position == 6 || $position == 'centerBottom'){
            //底部居中坐标
            $xy[0]=($image_w-$watermark_w)/2;
            $xy[1]=$image_h-$watermark_h;
        }elseif($position == 7 || $position == 'leftBottom'){
            //左下角坐标
            $xy[0]=0;
            $xy[1]=$image_h-$watermark_h;
        }elseif($position == 8 || $position == 'leftCentos'){
            //左侧居中坐标
            $xy[0]=0;
            $xy[1]=($image_h-$watermark_h)/2;
        }elseif($position == 9 || $position == 'center'){
            //图片垂直居中坐标
            $xy[0]=($image_w-$watermark_w)/2;
            $xy[1]=($image_h-$watermark_h)/2;
        }
        return $xy;
    }

    /**
     * 销毁内存中的图片
     * @param $res1 resource 图片资源
     * @param null $res2 resource 图片资源
     * @param null $res3 resource 图片资源
     */
    private function image_destroy(){
        if(is_resource($this->img_new)){
            imagedestroy($this->img_new);
        }
        if(is_resource($this->img_res)){
            imagedestroy($this->img_res);
        }
        if(is_resource($this->watermark_res)){
            imagedestroy($this->watermark_res);
        }

    }

    /**
     * @param $colorString
     * @return mixed Array $color 返回RGB颜色值
     */
    private function getFontColor($colorString)
    {
        //返回red,green,blue颜色值的数组
        switch ($colorString) {
            case 'red':
                return array(0 => 255, 1 => 0, 2 => 0);
            case 'pink':
                return array(0 => 252, 1 => 218, 2 => 252);
            case 'white':
                return array(0 => 255, 1 => 255, 2 => 255);
            case 'green':
                return array(0 => 0, 1 => 255, 2 => 0);
            case 'blue':
                return array(0 => 0, 1 => 0, 2 => 255);
            default:
                //未找到相应颜色则返回黑色
                return array(0 => 0, 1 => 0, 2 => 0);
        }
    }
}