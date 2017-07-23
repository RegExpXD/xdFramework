<?php
class core_template{
    /**
     * @param $fileName
     * @param $module 模块默认为当前SITE_CONT
     * @param $data
     */
    public static function render($fileName,$data,$module = SITE_CONT){
        if(empty($fileName)){
            return false;
        }

        $targetDir = TEMPLATE.'/'.$module;//模块存放模板文件目录
        $targetCommonDir = $targetDir.'/common';//模块公用文件夹，存放header.html footer.html之类的公用文件
        $targetFile = $targetDir.'/'.$fileName;//模板文件

        $compileDir = TEMPLATE_C.'/'.$module;//模块编译目录
        $compileCommonDir = $compileDir.'/common';//公用文件编译目录
        $compileFile = $compileDir.'/'.$fileName;//编译文件路径


        !is_dir($targetDir) && mkdir($targetDir,0755);
        !is_dir($compileDir) && mkdir($compileDir,0755);
        !is_dir($targetCommonDir) && mkdir($targetCommonDir,0755);
        !is_dir($compileCommonDir) && mkdir($compileCommonDir,0755);

        if(is_file($targetFile)){
            self::includeFile($targetFile,$compileFile);
            extract($data);
            include $compileFile;
        }else{//不存在模板文件
            show_404();exit;
        }
    }

    public static function commonFile($fileName,$module = SITE_CONT){
        $targetDir = TEMPLATE.'/'.$module.'/common';
        $targetFile = $targetDir.'/'.$fileName;

        $compileDir = TEMPLATE_C.'/'.$module.'/common';
        $compileFile = $compileDir.'/'.$fileName;

        !is_dir($targetDir) && mkdir($targetDir,0755);
        !is_dir($compileDir) && mkdir($compileDir,0755);

        self::includeFile($targetFile,$compileFile);
    }
    //编译文件不存在，或者模板文件最后修改时间大于编译文件最后修改时间，需要重新编译
    public static function includeFile($targetFile,$compileFile){
        if(is_file($targetFile)){
            if(!is_file($compileFile) || fileatime($targetFile) > fileatime($compileFile)){
                $targetFileContent = file_get_contents($targetFile);
                $compileFileContent = self::replaceTarget($targetFileContent);
                file_put_contents($compileFile,$compileFileContent);
            }
            include $compileFile;
        }else{
            show_404();exit;
        }
    }
    //替换模板文件标签
    public static function replaceTarget($str){
        $str = preg_replace('|<!--{if (.+)}-->|isU', '<?php if(\\1){ ?>', $str);
        $str = preg_replace('|<!--{/if}-->|isU', '<?php } ?>', $str);
        $str = preg_replace('|{_(.*)}|isU', '<?php echo \\1; ?>', $str);
        $str = preg_replace('|{\$lang_(.*)}|isU', '<?php echo $language["\\1"]; ?>', $str);
        $str = preg_replace('|{\$(.*)}|isU', '<?php echo $\\1; ?>', $str);
        $str = preg_replace('|<!--{else}-->|isU', '<?php }else{ ?>', $str);
        $str = preg_replace('|<!--{elseif (.*)}-->|isU', '<?php }elseif(\\1){ ?>', $str);
        $str = preg_replace('|{eval (.*)}|isU', '<?php \\1; ?>', $str);
        $str = preg_replace('|{echo (.*)}|isU', '<?php echo \\1; ?>', $str);
        $str = preg_replace('|{include_php (.*)}|isU', '<?php if(is_file("\\1.php")){ include_once("\\1.php");} ?>', $str);
        $str = preg_replace('|{include_htm(.*) (.*)}|isU', '<?php if(is_file("\\2.htm\\1")){ $str = file_get_contents("\\2.htm\\1"); echo $str;} ?>', $str);
        $str = preg_replace('|<!--{loop (.*) (.*) (.*)}-->|isU', '<?php if(is_array(\\1)){ foreach(\\1 as \\2 => \\3){  ?>', $str);
        $str = preg_replace('|<!--{for (.*)}-->|isU', '<?php for (\\1) {  ?>', $str);
        $str = preg_replace('|<!--{/for}-->|isU', '<?php } ?>', $str);
        $str = preg_replace('|<!--{/loop}-->|isU', '<?php }} ?>', $str);
        return $str;
    }
}