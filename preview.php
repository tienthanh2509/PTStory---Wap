<?php
include "config.php";
$id=$_GET["id"];
$q="select * from mobileshare where id='$id'";
$qu=mysql_query($q);
$row=mysql_fetch_array($qu);
$file=$row['file'];
$ftype=$row['ftype'];
if ($file==''){header("Content-type: image/gif");echo base64_decode("R0lGODlhGAAYAOZ/AP////+QN/+JPv+4hP/Lr/+UJ//r2//FjP/16P/Yg//lvP+kLv+VG//r0v+yFP+sIf/z2P/69P+UPf/+/f+tbP+NWf+MHf/Ttf/Ce/97Lf99Nv+2Kv+8Kf+zgv/qw//Xvf/Vkv/Flf+iJf+6S/+NMv+CNv/Zu/9zI/+pLf/z5v+yPv+NNf+EQ/+5Kv/EKP+VTP+LMv+WRv+9ev+YNv/8+f/38f+sPf/Jn/+WMf+FIv+HNf+hMP+uLv+jOf+eL/+lFv+WQ//MZv+xHP+SUf+5X//nw/+rFP+NQP+EKP+RQv97If9/J//v5f/MfP/Do/+UM/+dc/+9Y//TZP+jcf/24/+nYf+haP+dNf/KQv9uIv+4O//NqP+yLP+eF/+YMf/bwP+6F/+INv/m2f+wZf+3bP+DOf+5aP+cGP/gy/+BH/+pKP+xf/+xK/+RNP/w4f/47//NX/+rGf/WoP/dpP/eqP9nJv+lWP9vKP/Orv+5N/+IIf/Aa//EIf+9U/+2RP///yH5BAEAAH8ALAAAAAAYABgAAAf/gH+Cg4M0CVhSEISLjIRwYHxgHFSNlYIKDi6aDkGWjRNaQhyjHEYNnotzPy0brS1xI6iDETwPGwlvHnkbPwqyfyBdbH0TAAB0bA8qE6hvImooRQggHhMjPGdyqBgMKFEATSp+NAooIjsRlm4MCwsGAHs2NggARAsMB5ZkFj4YxmY9etBrsMNHgRSNGujxcSWFsTEzZjgEIMOHBRmN7OjBccAYAAokVkQw5maGlxwGFplI80RCDY8GOnzwCODAkxxVCE14gYTEDZoG1oihWQNIGyVoBuFRAiMGDZoV7rCgCeDGiiUvBNEok0HHGqpQ6lSgykRHmBMX/lzIUqKEFaoAS4ZS/dA2w5A/IU5o2DuFgN+/gAk4YbFXQ4A/JkgAOSKgsePHkAUkIUHhzwQKMGAE2My5s+cAMICkFPQlxIDTqFOrHrClxq/XsCsFAgA7");}

// Allocate all necessary memory for the image.
// Special thanks to Alecos for providing the code.
ini_set('memory_limit', '-1');

// include image processing code
include('includes/image.class.php');

$img = new Zubrag_image;

// initialize
$img->max_x        = 100;
$img->max_y        = 100;
$img->cut_x        = 0;
$img->cut_y        = 0;
$img->quality      = 100;
//NOT Edit
$img->save_to_file = false;
////////
$img->image_type   = -1;
// generate thumbnail
$img->GenerateThumbFile('uploads/'.$ftype.'/'.$file, '../cache');
?>