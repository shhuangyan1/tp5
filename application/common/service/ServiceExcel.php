<?php
/**
 * Created by PhpStorm.
 * User: vowkin
 * Date: 2017/5/22
 * Time: 21:49
 */

namespace app\common\service;


use SebastianBergmann\CodeCoverage\Report\PHP;

class ServiceExcel
{
    public static function excel_exchange($list, $excel_name, $code)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('PRC');
        ini_set('memory_limit','1024M');//设置导出最大内存
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        $excel = new ServiceExcel();
        $Ym = date('Y-m-d', time());
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中设置


        $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode("0");
        $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode("0");
        $obj = $objPHPExcel->getActiveSheet();
        $obj->setTitle($Ym . $excel_name);
        $AZ = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU',
        ];

        switch ($code) {
            case 'coursePost':
                $excel->excel_coursePostList($obj, $list, $AZ);
                break;
        }
//        halt($objPHPExcel);
        $exportName=$excel_name.' '.date('Y-m-d His',time()).'.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$exportName.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }



    //导购订单导出
    public function excel_coursePostList($obj, $list, $AZ){
        $i = 1;
        $col_index = [
            'add_ts' => 0, 'title' => 1, 'user_no' => 2,
            'real_name' => 3, 'mobile' => 4, 'company' => 5, 'position' => 6, 'note' => 7, 'status' => 8,
        ];//需要导出内容

        $titles = ['报名下单时间','课程名称','会员编号','会员','会员电话','公司名称','职位名称','备注信息', '状态'];

        foreach ($titles as $index => $v) {//标题填充
            $obj->setCellValue($AZ[$index] . $i, $v);
        }
        $i = 2;
        foreach($list as $info_arr){
            foreach ($info_arr as $c=>$info){
                if(!isset($col_index[$c])){
                    continue;
                }
                $obj->setCellValue($AZ[$col_index[$c]].$i, ' '.$info);
            }
            $i++;
        }
    }

    //excel表格导入信息
    public static function excel_import($file_name){
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>3145728,'ext'=>'xls,csv,xlsx'])->move(ROOT_PATH . 'public' . DS . 'uploads/'.$file_name);
        if($info) {
            $filename = $info->getSaveName();
            $filenames = ROOT_PATH . 'public' . DS . 'uploads/'.$file_name.'/' . $filename;
            vendor("PHPExcel.PHPExcel");
            $filetype = \PHPExcel_IOFactory::identify($filenames);//自动获取文件类型
            $objReader = \PHPExcel_IOFactory::createReader($filetype);//获取文件读取对象
            $objPHPExcel = $objReader->load($filenames);//加载excel文件
            $name = $objPHPExcel->getsheetNames();
            $sheetn = end($name);
            $objReader->setLoadSheetsOnly($sheetn);
            $objPHPExcel = $objReader->load($filenames);
            $sheetname = $objPHPExcel->getSheet();//获取当前sheet
            $data = $sheetname->toArray();
            if(!empty($data)){
                unset($data[0]);
                return $data;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}