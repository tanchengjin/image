# image
this is image process class
这是一个图片处理类
$image=newImage();
//可以使用链式操作来实现多个图片处理
//flip_x
$image->setPrefix('cc_')->flip_x('./image.jpg')->image_save('new','./','cc_');
/**
     * 图片保存
     * @param $fileName String 文件名
     * @param null $savePath String 文件保存路径
     * @param null $prefix 文件名前缀
     */
public function image_save($fileName,$savePath=null,$prefix=null)
<br>



/**
     * 图片水印
     * @param null $imagePath String 所要被添加水印的图片路径
     * @param null $watermarkImagePath String 水印图片路径
     * @param $position int|string 1左上角,2顶部居中,3右上角,4右侧居中,5右下角,6底部居中,7左下角,8左侧居中,9垂直居中
     * @return $this;
     */
    public function watermarkImage($imagePath=null,$watermarkImagePath=null,$position=5)
    

/**
     * 字符串水印
     * @param $imagePath String 需要被添加水印的图片路径
     * @param $content String 水印内容
     * @param $fontSize int 字体大小
     * @param int $position int|String 水印位置
     * @param String $fontColor string 字体颜色默认为黑色
     * @return $this;
     */
    public function watermarkString($imagePath=null,$content,$position=5,$fontSize=5,$fontColor='black')
    
    

/**
     * 以图片Y轴进行水平翻转
     * @param null $imagePath String 图片路径
     * @return $this
     */
public function flip_y($imagePath=null)


/**
     * 以图片X轴水平翻转图片
     * @param null $imagePath String 图片路径
     * @return $this;
     */
public function flip_x($imagePath=null)
    
    

    
/**
     * 图片裁剪
     * @param null $imagePath 图片路径
     * @param $x int 所要裁剪的图片x轴
     * @param $y int 所要裁剪的图片y轴
     * @param $width int 所要裁剪的图片宽度
     * @param $height int 所要裁剪的图片高度
     * @return $this
     */
public function cut($imagePath=null,$x,$y,$width,$height)

/**
     * 旋转图片
     * @param $angle int 所要旋转图片的角度
     * @param Null $imagepath String 所要旋转的图片路径
     * @return $this|bool
     */
public function rotate($angle,$imagepath=null)

/**
     * 将图片压缩成缩略图
     * @param null $imagePath 要压缩图片路径
     * @param int $width 缩略图宽度
     * @param int $height 缩略图高度
     * @return $this
     */
public function thumb($imagePath=null,$width=200,$height=200)

/**
     * 在浏览器中输出图片
     * @param bool $destroy 如果为true则销毁所有图片资源,如果为false则不进行销毁
     */
    public function image_print($destroy=true)
    
/**
     * 图片保存
     * @param $fileName String 文件名
     * @param null $savePath String 文件保存路径
     * @param null $prefix 文件名前缀
     */
public function image_save($fileName,$savePath=null,$prefix=null)
