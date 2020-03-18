<?php

/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */
use Joomunited\WPFramework\v1_0_5\Controller;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WptmControllerExcel
 */
class WptmControllerExcel extends Controller
{
    /**
     * Error message
     *
     * @var string $error_message error_message
     */
    private $error_message = '';

    /**
     * Allowed ext
     *
     * @var array $allowed_ext allowed_ext
     */
    private $allowed_ext = array('xls', 'xlsx');

    /**
     * Function import excell
     *
     * @return void
     */
    public function import()
    {
        $json = array();
        $file = Utilities::getInput('file', 'POST', 'string');

        $upload_dir = wp_upload_dir();
        $targetPath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
        if ($file) {
            $file = $targetPath . $file;
        } else {
            $file = $this->uploadFileExcel();
        }
        if ($file) {
            $id_table    = Utilities::getInt('id_table', 'POST');
            $onlydata    = Utilities::getInt('onlydata', 'POST');
            $ignoreCheck = Utilities::getInt('ignoreCheck', 'POST');

            $modelTable   = $this->getModel('table');
            $tableContent = (array) $modelTable->getItem($id_table);

            $readFileExcel = $this->readFileExcel($file, true, $onlydata === 0, false);

            if (!isset($readFileExcel['status']) || $readFileExcel['status'] !== true) {
                $this->exitStatus(esc_attr($readFileExcel['text']));
            }

            /*set data table*/
            $tableContent['datas'] = $readFileExcel['data']['datas'];

            /*set style table*/
            if (isset($tableContent['style']) && is_string($tableContent['style'])) {
                $tableContent['style'] = json_decode($tableContent['style'], false);
            } else {
                $tableContent['style'] = new stdClass();
            }

            if (!$onlydata) {
                $readFileExcel['data']['style']['table'] =  $tableContent['style']->table;
                $tableContent['style'] = $readFileExcel['data']['style'];
            }
            $tableContent['style'] = json_encode($tableContent['style']);

            /*set params table*/
            $tableContent['params']               = new stdClass();
            $tableContent['params']->mergeSetting = $readFileExcel['data']['params']['mergeSetting'];
            $hyperlinks = $readFileExcel['data']['params']['hyperlink'];

            if ($hyperlinks !== false) {
                $count = count($hyperlinks);
                if ($count > 0) {
                    $tableContent = $this->changeHyperlinksTable($tableContent, $hyperlinks);
                }
            } else {
                $hyperlinks = array();
            }
            $tableContent['params']->hyperlink = $hyperlinks;

            unlink($file);

            if (!$modelTable->save($id_table, $tableContent)) {
                $error = array(
                    'error' => $this->error_message,
                    'text'  => __('error while saving table', 'wptm')
                );
                $this->exitStatus($error);
            } else {
                $this->exitStatus(true, $tableContent);
            }
        } else {
            $this->exitStatus($this->error_message);
        }
    }

    /**
     * Function change type cell value to string and replace ',' to ';' in calculate cell
     *
     * @param array $datas      Data cell
     * @param array $valueDatas Data cell value
     * @param array $maxCell    Max number row and col
     *
     * @return mixed
     */
    public function changeValueCalculateCell($datas, $valueDatas, $maxCell)
    {
        for ($i = 0; $i < $maxCell['row']; $i++) {
            $datas[$i] = array_map(
                function ($data, $valueData) {
                    $data    = (string) $data;
                    $key     = substr($data, 0, 1);
                    $pattern = '@^=(DATE|DAY|DAYS|DAYS360|AND|OR|XOR|SUM|COUNT|MIN|MAX|AVG|CONCAT|date|day|days|days360|and|or|xor|sum|count|min|max|avg|concat)\\((.*?)\\)$@';
                    if ($key === '=' && preg_match($pattern, $data, $matches)) {
                        $data = str_replace(',', ';', $data);
                    } else {
                        $data = $valueData;
                    }
                    return (string) $data;
                },
                $datas[$i],
                $valueDatas[$i]
            );
        }
        return $datas;
    }

    /**
     * Function replace ';' to ',' of cell value in calculate cell
     *
     * @param array $datas   Data cell
     * @param array $maxCell Max number row and col
     *
     * @return mixed
     */
    public function renderValueCalculateCell($datas, $maxCell)
    {
        for ($i = 0; $i < $maxCell['row']; $i++) {
            $datas[$i] = array_map(
                function ($data) {
                    $data    = (string) $data;
                    $key     = substr($data, 0, 1);
                    $pattern = '@^=(DATE|DAY|DAYS|DAYS360|AND|OR|XOR|SUM|COUNT|MIN|MAX|AVG|CONCAT|date|day|days|days360|and|or|xor|sum|count|min|max|avg|concat)\\((.*?)\\)$@';
                    if ($key === '=' && preg_match($pattern, $data, $matches)) {
                        $data = str_replace(';', ',', $data);
                    }
                    return $data;
                },
                $datas[$i]
            );
        }
        return $datas;
    }

    /**
     * Function creator array list data merge cell
     *
     * @param array $mergeRanges Value return $sheetActive->getMergeCells
     *
     * @return array
     */
    public function getMergeCell($mergeRanges)
    {
        $mergeSettings = array();

        foreach ($mergeRanges as $mergeRange) {
            list($tlCell, $rbCell) = explode(':', $mergeRange);

            list($tl_cNb, $tl_rNb) = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($tlCell);
            list($br_cNb, $br_rNb) = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($rbCell);
            $tl_cNb = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($tl_cNb);
            $br_cNb = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($br_cNb);

            $mergeSetting          = new stdClass();
            $mergeSetting->row     = $tl_rNb - 1;
            $mergeSetting->col     = $tl_cNb - 1;
            $mergeSetting->rowspan = $br_rNb - $tl_rNb + 1;
            $mergeSetting->colspan = $br_cNb - $tl_cNb + 1;
            $mergeSettings[]       = $mergeSetting;
        }
        return $mergeSettings;
    }
    /**
     * Convert utf8
     *
     * @param array $array Array
     *
     * @return mixed
     */
    public function utf8Converter($array)
    {
        ini_set('mbstring.substitute_character', 'none');
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            }
        });

        return $array;
    }

    /**
     * Create CSS style
     *
     * @param PhpOffice\PhpSpreadsheet\Style\Style $pStyle PhpOffice\PhpSpreadsheet\Style\Style
     *
     * @return array
     */
    private function createCSSStyleExel(PhpOffice\PhpSpreadsheet\Style\Style $pStyle)
    {
        // Construct CSS
        $css = '';

        $css = array_merge(
            $this->createCSSStyleAlignmentExcel($pStyle->getAlignment()),
            $this->createCSSStyleBordersExcel($pStyle->getBorders()),
            $this->createCSSStyleFontExcel($pStyle->getFont()),
            $this->createCSSStyleFillExcel($pStyle->getFill())
        );

        // Return
        return $css;
    }

    /**
     * Create CSS style (PhpSpreadsheet style alignment)
     *
     * @param PhpOffice\PhpSpreadsheet\Style\Alignment $getAlignment PhpOffice\PhpSpreadsheet\Style\Alignment
     *
     * @return array
     */
    private function createCSSStyleAlignmentExcel(PhpOffice\PhpSpreadsheet\Style\Alignment $getAlignment)
    {
        // Construct CSS
        $css = array();

        // Create CSS
        $css['cell_vertical_align'] = $this->mapVAlignExcel($getAlignment->getVertical());
        $textAlign                  = $this->mapHAlignExcel($getAlignment->getHorizontal());
        if ($textAlign) {
            $css['cell_text_align'] = $textAlign;
            if (in_array($textAlign, array('left', 'right'))) {
                $css['cell_padding_' . $textAlign] = (string) ((int) $getAlignment->getIndent() * 9) . 'px';
            }
        }

        // Return
        return $css;
    }

    /**
     * Create CSS style (PhpSpreadsheet style font)
     *
     * @param PhpOffice\PhpSpreadsheet\Style\Font $pStyle PhpOffice\PhpSpreadsheet\Style\Font
     *
     * @return array
     */
    private function createCSSStyleFontExcel(PhpOffice\PhpSpreadsheet\Style\Font $pStyle)
    {
        // Construct CSS
        $css = array();

        // Create CSS
        if ($pStyle->getBold()) {
            $css['cell_font_bold'] = true;
        }
        if ((string) $pStyle->getUnderline() !== PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE && $pStyle->getStrikethrough()) {
            $css['cell_font_underline'] = true;
        } elseif ((string) $pStyle->getUnderline() !== PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE) {
            $css['cell_font_underline'] = true;
        } elseif ($pStyle->getStrikethrough()) {
            $css['cell_font_underline'] = false;
        }
        if ($pStyle->getItalic()) {
            $css['cell_font_italic'] = true;
        }

        $css['cell_font_color']  = '#' . $pStyle->getColor()->getRGB();
        $css['cell_font_family'] = $pStyle->getName();
        $css['cell_font_size']   = floor($pStyle->getSize() * 96 / 72); //points = pixels * 72 / 96
        // Return
        return $css;
    }

    /**
     * Create CSS style (PhpSpreadsheet style borders)
     *
     * @param PhpOffice\PhpSpreadsheet\Style\Borders $pStyle PhpOffice\PhpSpreadsheet\Style\Borders
     *
     * @return array
     */
    private function createCSSStyleBordersExcel(PhpOffice\PhpSpreadsheet\Style\Borders $pStyle)
    {
        // Construct CSS
        $css = array();

        // Create CSS
        $css['cell_border_bottom'] = $this->createCSSStyleBorderExcel($pStyle->getBottom());
        $css['cell_border_top']    = $this->createCSSStyleBorderExcel($pStyle->getTop());
        $css['cell_border_left']   = $this->createCSSStyleBorderExcel($pStyle->getLeft());
        $css['cell_border_right']  = $this->createCSSStyleBorderExcel($pStyle->getRight());

        // Return
        return $css;
    }

    /**
     * Create CSS style (PhpSpreadsheet style border)
     *
     * @param PhpOffice\PhpSpreadsheet\Style\Border $pStyle PhpOffice\PhpSpreadsheet\Style\Border
     *
     * @return string
     */
    private function createCSSStyleBorderExcel(PhpOffice\PhpSpreadsheet\Style\Border $pStyle)
    {
        // Create CSS
        // $css = $this->mapBorderStyleExcel($pStyle->getBorderStyle()) . ' #' . $pStyle->getColor()->getRGB();
        // Create CSS - add !important to non-none border styles for merged cells
        $borderStyle = $this->mapBorderStyleExcel($pStyle->getBorderStyle());
        $css         = $borderStyle . ' #' . $pStyle->getColor()->getRGB() . (($borderStyle === 'none') ? '' : ' !important');

        // Return
        return $css;
    }

    /**
     * Create CSS style (PhpSpreadsheet style fill)
     *
     * @param PhpOffice\PhpSpreadsheet\Style\Fill $pStyle PhpOffice\PhpSpreadsheet\Style\Fill
     *
     * @return array
     */
    private function createCSSStyleFillExcel(PhpOffice\PhpSpreadsheet\Style\Fill $pStyle)
    {
        // Construct HTML
        $css = array();

        // Create CSS
        $value                        = $pStyle->getFillType() === PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE ?
            '' : '#' . $pStyle->getStartColor()->getRGB();
        $css['cell_background_color'] = $value;

        // Return
        return $css;
    }

    /**
     * Map VAlign
     *
     * @param string $vAlign Vertical alignment
     *
     * @return string
     */
    private function mapVAlignExcel($vAlign)
    {
        switch ($vAlign) {
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM:
                return 'bottom';
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP:
                return 'top';
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER:
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY:
                return 'middle';
            default:
                return 'baseline';
        }
    }

    /**
     * Map HAlign
     *
     * @param string $hAlign Horizontal alignment
     *
     * @return string|false
     */
    private function mapHAlignExcel($hAlign)
    {
        switch ($hAlign) {
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_GENERAL:
                return false;
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT:
                return 'left';
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT:
                return 'right';
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER:
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER_CONTINUOUS:
                return 'center';
            case \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY:
                return 'justify';
            default:
                return false;
        }
    }

    /**
     * Map border style
     *
     * @param integer $borderStyle Sheet index
     *
     * @return string
     */
    private function mapBorderStyleExcel($borderStyle)
    {
        switch ($borderStyle) {
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE:
                return 'none';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOT:
                return '1px dashed';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOTDOT:
                return '1px dotted';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED:
                return '1px dashed';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED:
                return '1px dotted';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE:
                return '3px double';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR:
                return '1px solid';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM:
                return '2px solid';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOT:
                return '2px dashed';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOTDOT:
                return '2px dotted';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED:
                return '2px dashed';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_SLANTDASHDOT:
                return '2px dashed';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK:
                return '3px solid';
            case PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN:
                return '1px solid';
            default:
                return '1px solid'; // map others to thin
        }
    }

    /**
     * Upload file excel
     *
     * @return boolean|string
     */
    private function uploadFileExcel()
    {
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file'];
            //check file extension
            $tempFile['name'] = html_entity_decode($tempFile['name']);
            $ext              = strtolower(pathinfo($tempFile['name'], PATHINFO_EXTENSION));
            $newname          = uniqid() . '.' . $ext;
            if (!in_array($ext, $this->allowed_ext)) {
                $this->error_message = __('Wrong file extension', 'wptm');
                return false;
            }

            $upload_dir = wp_upload_dir();
            $targetPath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0777, true);
                $data = '<html><body bgcolor="#FFFFFF"></body></html>';
                $file = fopen($targetPath . 'index.html', 'w');
                fwrite($file, $data);
                fclose($file);
            }

            $targetFile = $targetPath . $newname;
            if (!move_uploaded_file($tempFile['tmp_name'], $targetFile)) {
                $this->error_message = __('Error orcured when retrieving file to temporary folder', 'wptm');
                return false;
            } else {
                return $targetFile;
            }
        } else {
            $this->error_message = __('Please choose a file before submit!', 'wptm');
            return false;
        }
    }

    /**
     * Function export
     *
     * @param null|array $dataTable Data table export
     *
     * @return void
     */
    public function export($dataTable = null)
    {
        $file = $this->makeFileExcel($dataTable);
        if ($file && is_readable($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);

            if (file_exists($file)) {
                unlink($file);
            }
        } else {
            $this->exitStatus(sprintf('%s', $this->error_message));
        }
        die();
    }

    /**
     * Function make file
     *
     * @param null|array $dataTable Data table export
     *
     * @return boolean|resource|string
     */
    private function makeFileExcel($dataTable)
    {
        $format_excel = Utilities::getInput('format_excel');
        $id           = Utilities::getInt('id', 'GET');
        $onlydata     = Utilities::getInt('onlydata', 'GET');
        if ($dataTable === null) {
            $modelTable   = $this->getModel('table');
            $tableContent = $modelTable->getItem($id);
        } else {
            $tableContent = $dataTable;
        }
        $datas = json_decode($tableContent->datas, 1);

        if (!is_array($datas)) {
            $this->error_message = __('Table is not existed! Please choose one another.', 'wptm');
            return false;
        } else {
            $folder_admin = dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'admin';
            require_once $folder_admin . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'phpspreadsheet' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();

            $activeSheet->fromArray($datas);
            $maxCell     = $activeSheet->getHighestRowAndColumn();

            $datas = $this->renderValueCalculateCell($datas, $maxCell);

            $activeSheet->fromArray($datas);
            
            if ($format_excel === 'xlsx') {
                $activeSheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row'], '', false);
            } else {
                $data = $activeSheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row'], '', true);
                $activeSheet->fromArray($data);
            }


            if (!$onlydata) {
                $tblStyles = json_decode($tableContent->style, true);

                /*set height cells*/
                if (isset($tblStyles['rows'])) {
                    $rowStyles = $tblStyles['rows'];
                    if (!empty($rowStyles)) {
                        $rI = 0;
                        foreach ($rowStyles as $row) {
                            $rI ++;
                            if (isset($row[1]) && isset($row[1]['height']) && $row[1]['height']) {
                                $activeSheet->getRowDimension($rI)->setRowHeight(floor($row[1]['height'] / 1.333333)); //px 2 pt
                            }
                        }
                    }
                }

                /*set width cells*/
                if (isset($tblStyles['cols'])) {
                    $colStyles = $tblStyles['cols'];
                    if (!empty($colStyles)) {
                        $cI = 0;
                        foreach ($colStyles as $col) {
                            if (isset($col[1]) && isset($col[1]['width']) && $col[1]['width']) {
                                $activeSheet->getColumnDimensionByColumn($cI + 1)->setWidth(floor($col[1]['width'] / 10)); //Excel unit: number of characters that can be displayed with the standard font
                            }
                            $cI ++;
                        }
                    }
                }

                /*set style table*/
                if (isset($tblStyles['table'])) {
                    $tableStyles = $tblStyles['table'];
                    if (!empty($tableStyles)) {
                        $activeSheet = $this->setStyleTable($activeSheet, $tableStyles);
                    }
                } else {
                    $tableStyles = array();
                }

                /*set style cells*/
                if (isset($tblStyles['cells'])) {
                    $cellStyles = $tblStyles['cells'];
                    if (!empty($cellStyles)) {
                        foreach ($cellStyles as $key => $cellCss) {
                            $check_valid_cells = explode('!', $key);
                            if (isset($check_valid_cells[0]) && (int) $check_valid_cells[0] >= 0) {
                                $activeSheet = $this->setCellStyle($activeSheet, $cellCss[1] + 1, $cellCss[0] + 1, $cellCss[2], $tableStyles);
                            }
                        }
                    }
                }

                if (gettype($tableContent->params) === 'string') {
                    $tableParams = json_decode($tableContent->params, true);
                } else {
                    $tableParams = json_encode($tableContent->params);
                    $tableParams = json_decode($tableParams, true);
                }

                if (isset($tableParams['mergeSetting'])) {
                    $mergeSettings = json_decode($tableParams['mergeSetting'], true);
                } else {
                    $mergeSettings = array();
                }

                if (!empty($mergeSettings)) {
                    foreach ($mergeSettings as $mergeSetting) {
                        $activeSheet->mergeCellsByColumnAndRow($mergeSetting['col'] + 1, $mergeSetting['row'] + 1, $mergeSetting['col'] + $mergeSetting['colspan'], $mergeSetting['row'] + $mergeSetting['rowspan']);
                    }
                }
            }

            if ($format_excel === 'xlsx') {
                $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            } elseif ($format_excel === 'xls') {
                $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
            }

            $upload_dir = wp_upload_dir();
            $targetPath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;

            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0777, true);
                $data = '<html><body bgcolor="#FFFFFF"></body></html>';
                $file = fopen($targetPath . 'index.html', 'w');
                fwrite($file, $data);
                fclose($file);
            }

            $tableContent->title = strtolower(preg_replace('/[^a-z0-9_]+/i', '_', $tableContent->title));
            $file                = $targetPath . DIRECTORY_SEPARATOR . $tableContent->title . '_' . $id . '.' . $format_excel;

            try {
                $objWriter->save($file);
            } catch (Exception $e) {
                $this->error_message = __('Error occurred when creating file to export! <br/>Please try again.', 'wptm');
            }

            if (!file_exists($file) || !is_readable($file)) {
                $this->error_message = __('Error occurred when creating file to export! <br/>Please try again.', 'wptm');
                return false;
            }
        }

        return $file;
    }

    /**
     * Function set style of table(freeze_row, col, symbol...)
     *
     * @param object $activeSheet ActiveSheet
     * @param array  $tableStyle  Data style table
     *
     * @return mixed
     */
    public function setStyleTable($activeSheet, $tableStyle)
    {
        /*freeze col and arow*/
        if (isset($tableStyle['freeze_col']) && $tableStyle['freeze_col']) {
            $freeze_col = (int) $tableStyle['freeze_col'] + 1;
        } else {
            $freeze_col = 1;
        }

        if (isset($tableStyle['freeze_row']) && $tableStyle['freeze_row']) {
            $freeze_row = (int) $tableStyle['freeze_row'] + 1;
        } else {
            $freeze_row = 1;
        }

        if ($freeze_row * $freeze_col !== 1) {
            $activeSheet->freezePaneByColumnAndRow($freeze_col, $freeze_row);
        }

        return $activeSheet;
    }

    /**
     * Function set style for cell
     *
     * @param object  $activeSheet ActiveSheet
     * @param integer $col         Col
     * @param integer $row         Row
     * @param array   $css         Css
     * @param array   $tableStyles Data option table
     *
     * @return mixed
     */
    public function setCellStyle($activeSheet, $col, $row, $css, $tableStyles)
    {
        //font
        if (isset($css['cell_font_bold']) && $css['cell_font_bold']) {
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
        }
        if (isset($css['cell_font_underline']) && $css['cell_font_underline']) {
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFont()->setUnderline(true);
        }
        if (isset($css['cell_font_italic']) && $css['cell_font_italic']) {
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFont()->setItalic(true);
        }
        if (isset($css['cell_font_color']) && $css['cell_font_color']) {
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFont()->getColor()->setRGB(str_replace('#', '', $css['cell_font_color']));
        }
        if (isset($css['cell_font_family']) && $css['cell_font_family']) {
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFont()->setName($css['cell_font_family']);
        }
        if (isset($css['cell_font_size']) && $css['cell_font_size']) {
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFont()->setSize($css['cell_font_size'] * 72 / 96); //points = pixels * 72 / 96
        }

        //Alignment
        if (isset($css['cell_vertical_align']) && $css['cell_vertical_align']) {
            if ($css['cell_vertical_align'] === 'middle') {
                $vertical = 'center';
            } else {
                $vertical = $css['cell_vertical_align'];
            }
            $activeSheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setVertical($vertical);
        }
        if (isset($css['cell_text_align']) && $css['cell_text_align']) {
            $horizontal = $css['cell_text_align'];
            $activeSheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal($horizontal);
        }

        //Fill
        if (!isset($css['cell_background_color']) || isset($css['cell_background_color']) && $css['cell_background_color'] === '') {
            if (isset($css['AlternateColor'])) {
                $css['cell_background_color'] = $this->getAlternateColor($row - 1, $css, $tableStyles);
            }
        }

        if (isset($css['cell_background_color'])) {
            $fill_color = str_replace('#', '', $css['cell_background_color']);
            if ($fill_color === null || $fill_color === '') {
                $fill_color = 'ffffff';
            }
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFill()->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $activeSheet->getStyleByColumnAndRow($col, $row)->getFill()->getStartColor()->setRGB($fill_color);
        }

        //Border
        if (isset($css['cell_border_bottom']) && $css['cell_border_bottom']) {
            list($bWidth, $bStyle, $bColor) = array_merge(explode(' ', $css['cell_border_bottom']), array('', ''));
            if ($bColor !== '') {
                $borderStyle = $this->getBorderStyle($bWidth, $bStyle);
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->setBorderStyle($borderStyle);
                $bColor = str_replace('#', '', trim($bColor));
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getBottom()->getColor()->setRGB($bColor);
            }
        }

        if (isset($css['cell_border_top']) && $css['cell_border_top']) {
            list($bWidth, $bStyle, $bColor) = array_merge(explode(' ', $css['cell_border_top']), array('', ''));
            if ($bColor !== '') {
                $borderStyle = $this->getBorderStyle($bWidth, $bStyle);
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getTop()->setBorderStyle($borderStyle);
                $bColor = str_replace('#', '', trim($bColor));
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getTop()->getColor()->setRGB($bColor);
            }
        }

        if (isset($css['cell_border_left']) && $css['cell_border_left']) {
            list($bWidth, $bStyle, $bColor) = array_merge(explode(' ', $css['cell_border_left']), array('', ''));
            if ($bColor !== '') {
                $borderStyle = $this->getBorderStyle($bWidth, $bStyle);
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getLeft()->setBorderStyle($borderStyle);
                $bColor = str_replace('#', '', trim($bColor));
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getLeft()->getColor()->setRGB($bColor);
            }
        }

        if (isset($css['cell_border_right']) && $css['cell_border_right']) {
            list($bWidth, $bStyle, $bColor) = array_merge(explode(' ', $css['cell_border_right']), array('', ''));
            if ($bColor !== '') {
                $borderStyle = $this->getBorderStyle($bWidth, $bStyle);
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getRight()->setBorderStyle($borderStyle);
                $bColor = str_replace('#', '', trim($bColor));
                $activeSheet->getStyleByColumnAndRow($col, $row)->getBorders()->getRight()->getColor()->setRGB($bColor);
            }
        }

        return $activeSheet;
    }

    /**
     * Function set background color cell by alternate color
     *
     * @param integer $row         Row number
     * @param array   $css         Data css cell
     * @param array   $tableStyles Table styles
     *
     * @return string
     */
    public function getAlternateColor($row, $css, $tableStyles)
    {
        $fill_color = '';
        if (!isset($tableStyles['alternateColorValue']) && !isset($tableStyles['alternateColorValue'][$css['AlternateColor']])) {
            return $fill_color;
        } else {
            $alternateColorValue = $tableStyles['alternateColorValue'][$css['AlternateColor']];
        }

        $numberRow = 0;
        if ($alternateColorValue['header'] === '') {
            $numberRow = - 1;
        }
        switch ($row) {
            case $alternateColorValue['selection'][0]:
                if ($numberRow === - 1) {
                    $fill_color .= $alternateColorValue['even'];
                } else {
                    $fill_color .= $alternateColorValue['header'];
                }
                break;
            case $alternateColorValue['selection'][2]:
                if ($alternateColorValue['footer'] === '') {
                    if (($row - (int) ($alternateColorValue['selection'][0] + $numberRow)) % 2) {
                        $fill_color .= $alternateColorValue['even'];
                    } else {
                        $fill_color .= $alternateColorValue['old'];
                    }
                } else {
                    $fill_color .= $alternateColorValue['footer'];
                }
                break;
            default:
                if (($row - (int) ($alternateColorValue['selection'][0] + $numberRow)) % 2) {
                    $fill_color .= $alternateColorValue['even'];
                } else {
                    $fill_color .= $alternateColorValue['old'];
                }
                break;
        }

        return $fill_color;
    }

    /**
     * Function get Border Style cell
     *
     * @param integer $bWidth Width
     * @param string  $bStyle Style
     *
     * @return string
     */
    public function getBorderStyle($bWidth, $bStyle)
    {
        $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE;
        $bStyle      = trim($bStyle);
        $bWidth      = (int) $bWidth;
        if ($bWidth > 1) {
            switch ($bStyle) {
                case 'solid':
                    $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM;
                    break;
                case 'dashed':
                    $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED;
                    break;
                case 'dotted':
                    $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOT;
                    break;
            }
        } elseif ((int) $bWidth === 1) {
            switch ($bStyle) {
                case 'solid':
                    $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
                    break;
                case 'dashed':
                    $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED;
                    break;
                case 'dotted':
                    $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED;
                    break;
            }
        } else {
            $borderStyle = PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE;
        }

        return $borderStyle;
    }

    /**
     * Function syncSpreadsheet
     *
     * @return boolean|integer
     */
    public function syncSpreadsheet()
    {
        global $wpdb;
        $result = $wpdb->query('SELECT c.* FROM ' . $wpdb->prefix . 'wptm_tables as c ORDER BY c.id_category ASC, c.position ASC ');
        if ($result === false) {
            return false;
        }
        $tables = stripslashes_deep($wpdb->get_results('SELECT c.* FROM ' . $wpdb->prefix . 'wptm_tables as c ORDER BY c.id_category ASC, c.position ASC ', OBJECT));

        $count = 0;
        foreach ($tables as $table) {
            $tblStyles = json_decode($table->style, true);

            if (isset($tblStyles['table']) && isset($tblStyles['table']['spreadsheet_url']) && $tblStyles['table']['spreadsheet_url']) {
                $spreadsheet_url   = $tblStyles['table']['spreadsheet_url'];
                $auto_sync         = isset($tblStyles['table']['auto_sync']) ? (int) $tblStyles['table']['auto_sync'] : 0;
                $spreadsheet_style = isset($tblStyles['table']['spreadsheet_style']) ? (int) $tblStyles['table']['spreadsheet_style'] : 0;
                if ($auto_sync) {
                    if ($this->updateTableFromSpreadsheet($table->id, $spreadsheet_url, $spreadsheet_style)) {
                        $count ++;
                    }
                }
            }
        }
        return $count; //number of table synced
    }

    /**
     * Function fetch Spread sheet
     *
     * @return void
     */
    public function fetchSpreadsheet()
    {
        $id_table        = Utilities::getInt('id', 'POST');
        $autoSync        = Utilities::getInt('sync', 'POST');
        $fetchStyle      = Utilities::getInt('style', 'POST');
        $spreadsheet_url = Utilities::getInput('spreadsheet_url', 'POST', 'none');

        if ($id_table && $spreadsheet_url) {
            $update = $this->updateTableFromSpreadsheet($id_table, $spreadsheet_url, $fetchStyle);
            if (!$update) {
                $this->exitStatus(__('error while saving table', 'wptm'));
            } else {
                $this->exitStatus(true, array('sync'            => $autoSync,
                                              'style'           => $fetchStyle,
                                              'spreadsheet_url' => $spreadsheet_url
                ));
            }
        }

        $this->exitStatus(true, array('sync'            => $autoSync,
                                      'style'           => $fetchStyle,
                                      'spreadsheet_url' => $spreadsheet_url
        ));
    }

    /**
     * Function update Table From Spread sheet
     *
     * @param integer $id_table        Id of table
     * @param string  $spreadsheet_url Url
     * @param integer $fetchStyle      Fetch style table
     *
     * @return boolean
     */
    public function updateTableFromSpreadsheet($id_table, $spreadsheet_url, $fetchStyle)
    {
        $modelTable   = $this->getModel('table');
        $tableContent = (array) $modelTable->getItem($id_table);
        $doUpdate     = false;

        $folder_admin = dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'admin';
        require_once $folder_admin . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'phpspreadsheet' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

        if (strpos($spreadsheet_url, 'docs.google.com/spreadsheet') !== false) {
            //convert to url export csv
            $url_arr = explode('/', $spreadsheet_url);
            array_pop($url_arr);
            $csv_url = implode('/', $url_arr) . '/pub?hl=en_US&single=true&output=csv';

            $url_query = parse_url($spreadsheet_url, PHP_URL_QUERY);
            if (!empty($url_query)) {
                parse_str($url_query, $url_query_params);
                if (isset($url_query_params['gid'])) {
                    $csv_url .= '&gid=' . $url_query_params['gid'];
                }
            }

            if (!isset($tableContent['style'])) {
                $tableContent['style'] = '';
            }
            /*download file .csv*/
            $file = $this->getCsvDataFromUrl($csv_url, $spreadsheet_url);

            if ($file !== false) {
                $readFileExcel = $this->readFileExcel($file, true, $fetchStyle === 1, false);
                if (isset($readFileExcel['status']) && $readFileExcel['status']) {
                    $doUpdate = true;
                } else {
                    $this->exitStatus(esc_attr($readFileExcel['text']));
                }
            } else {
                return false;
            }
        } else {
            //download file
            $file = $this->downloadFileExcel($spreadsheet_url);
            if ($file) {
                $readFileExcel = $this->readFileExcel($file, true, $fetchStyle === 1, false);
                if (isset($readFileExcel['status']) && $readFileExcel['status']) {
                    $doUpdate = true;
                } else {
                    $this->exitStatus(esc_attr($readFileExcel['text']));
                }
            } else {
                return false;
            }
        }

        $updated = false;
        if (isset($readFileExcel)) {
            $tableContent['datas'] = $readFileExcel['data']['datas'];

            if (isset($tableContent['params']) && is_string($tableContent['params'])) {
                $tableContent['params']               = json_decode($tableContent['params'], true);
                $tableContent['params']->mergeSetting = $readFileExcel['data']['params']['mergeSetting'];
            } else {
                $tableContent['params']               = new stdClass();
                $tableContent['params']->mergeSetting = $readFileExcel['data']['params']['mergeSetting'];
            }

            if (isset($tableContent['style']) && is_string($tableContent['style'])) {
                $tableContent['style'] = json_decode($tableContent['style'], false);
            } else {
                $tableContent['style'] = new stdClass();
            }

            if (!isset($tableContent['style']->table)) {
                $tableContent['style']        = new stdClass();
                $tableContent['style']->table = new stdClass();
            }
            /*save spreadsheet_style and spreadsheet_url*/
            $tableContent['style']->table->spreadsheet_style = $fetchStyle;

            $spreadsheet_url   = str_replace('%20', ' ', $spreadsheet_url);
            $tableContent['style']->table->spreadsheet_url   = $spreadsheet_url;

            if ($fetchStyle === 1) {
                $readFileExcel['data']['style']['table'] =  $tableContent['style']->table;
                $tableContent['style'] = $readFileExcel['data']['style'];
            }
            unlink($file);
            $tableContent['style'] = json_encode($tableContent['style']);

            $hyperlinks = $readFileExcel['data']['params']['hyperlink'];
            if ($hyperlinks !== false) {
                $count = count($hyperlinks);
                if ($count > 0) {
                    $tableContent = $this->changeHyperlinksTable($tableContent, $hyperlinks);
                }
            } else {
                $hyperlinks = array();
            }

            $tableContent['params']->hyperlink = $hyperlinks;

            if ($doUpdate) {
                if ($modelTable->save($id_table, $tableContent)) {
                    $updated = true;
                }
            }
        }

        return $updated;
    }

    /**
     * Function read data file excel(import, fetch)
     *
     * @param string  $file        Url file excel
     * @param boolean $data        Check get data cell
     * @param boolean $style       Check get style table
     * @param boolean $ignoreCheck Ignore check
     *
     * @return string|array
     */
    public function readFileExcel($file, $data, $style, $ignoreCheck)
    {
        $tableContent = array();

        $folder_admin = dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'admin';
        require_once $folder_admin . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'phpspreadsheet' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

            //Load $inputFileName to a Spreadsheet Object
            $sheet       = $spreadsheet->getActiveSheet();
            $maxCell     = $sheet->getHighestRowAndColumn();
            // If there are more than 100 rows we need to re-check number of rows actually have data to avoid a sheet with many empty rows
            if ($maxCell['row'] > 100) {
                $valueDatas = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row'], '', false, true, false);
                //we need to first know how many rows actually have data
                $row_count = $maxCell['row'];
                // read through the data and see how many rows actually have data
                //the idea is that for every row, the first or second cell should be mandatory...
                //if we find one that is not, we stop there...
                do {
                    $row_count --;
                } while ((!$valueDatas[$row_count][0] || $valueDatas[$row_count][0] === 'NULL') &&
                         (!$valueDatas[$row_count][1] || $valueDatas[$row_count][1] === 'NULL')
                );

                $maxCell['row'] = $row_count + 1;
            }

            if ($data) {
                $valueDatas = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row'], '', true, true, false);
                $datas      = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row'], '', false, true, false);
                $valueDatas = $this->utf8Converter($valueDatas);
                $datas      = $this->utf8Converter($datas);

                /*change type cell value to string and replace ',' to ';' in calculate*/
                $datas = $this->changeValueCalculateCell($datas, $valueDatas, $maxCell);
            }
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return $e->getMessage();
        }

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            //phpcs:ignore PHPCompatibility.PHP.NewConstants.json_unescaped_unicodeFound -- the use of JSON_UNESCAPED_UNICODE has check PHP version
            $tableContent['datas'] = json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            $tableContent['datas'] = json_encode($datas);
        }
        //fix double quote in cell content
        $tableContent['datas'] = str_replace('\\', '\\\\', $tableContent['datas']);

        if (empty($tableContent['datas'])) {
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    return array('text' => ' - No errors', 'status' => false);
                case JSON_ERROR_DEPTH:
                    return array('text' => ' - Maximum stack depth exceeded', 'status' => false);
                case JSON_ERROR_STATE_MISMATCH:
                    return array('text' => ' - Underflow or the modes mismatch', 'status' => false);
                case JSON_ERROR_CTRL_CHAR:
                    return array('text' => ' - Unexpected control character found', 'status' => false);
                case JSON_ERROR_SYNTAX:
                    return array('text' => ' - Syntax error, malformed JSON', 'status' => false);
                // phpcs:ignore PHPCompatibility.PHP.NewConstants.json_error_utf8Found -- the use of JSON_ERROR_UTF8 has check
                case JSON_ERROR_UTF8:
                    return array('text'   => ' - Malformed UTF-8 characters, possibly incorrectly encoded',
                                 'status' => false
                    );
                default:
                    return array('text' => ' - Unknown error', 'status' => false);
            }
        }

        if (!$ignoreCheck && (($maxCell['column'] >= 500) || ($maxCell['row'] >= 500))) {
            return array(
                'text'   => __("Note: The spreadsheet you're trying to import has more than 500 rows or columns. It may be impossible to run the import depending of your server memory limit", 'wptm'),
                'file'   => basename($file),
                'status' => false
            );
        }

        $hyperlinks = $this->getHyperlinkFromgg($file, 0);

        if ($style) {
            $maxColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxCell['column']);
            $ci          = 0;
            /*change width cols of table*/
            $tblStyles['cols'] = array();
            for ($ci = 0; $ci < $maxColIndex; $ci ++) {
                $width                     = $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1))->getWidth();
                $tblStyles['cols'][$ci][0] = $ci;
                if ($width === - 1) {
                    $width = 10;
                }
                $tblStyles['cols'][$ci][1]['width'] = $width * 10;
            }

            foreach ($sheet->getColumnDimensions() as $cd) {
                $tblStyles['cols'][$ci][0]          = $ci;
                $tblStyles['cols'][$ci][1]['width'] = $cd->getWidth() * 10; //Excel unit: number of characters that can be displayed with the standard font
                $ci ++;
            }

            /*change height row table*/
            $tblStyles['rows'] = array();
            for ($ri = 0; $ri < $maxCell['row']; $ri ++) {
                $height                    = $sheet->getRowDimension($ri + 1)->getRowHeight();
                $tblStyles['rows'][$ri][0] = $ri;
                if ($height === - 1) {
                    $height = 18;
                }
                $tblStyles['rows'][$ri][1]['height'] = floor($height * 1.333333); ////1 point = 1.333333 px
            }

            $tblStyles['cells'] = array();
            for ($ri = 0; $ri < $maxCell['row']; $ri ++) {
                for ($ci = 0; $ci < $maxColIndex; $ci ++) {
                    $tblStyles['cells'][$ri . '!' . $ci]    = array($ri, $ci);
                    $cellStyle                              = $sheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1) . ($ri + 1));
                    $cellCss                                = $this->createCSSStyleExel($cellStyle);
                    $tblStyles['cells'][$ri . '!' . $ci][2] = $cellCss;
                }
            }

            $tblStyles['table']    = new stdClass();
            $tableContent['style'] = $tblStyles;
        }

        //Read Merged Cells info
        $mergeSettings = array();
        $mergeRanges   = $spreadsheet->getActiveSheet()->getMergeCells();
        if (count($mergeRanges)) {
            $mergeSettings = $this->getMergeCell($mergeRanges);
        }
        $tableContent['params'] = array(
            'mergeSetting' => json_encode($mergeSettings),
            'hyperlink'    => $hyperlinks
        );

        return array('data' => $tableContent, 'status' => true);
    }

    /**
     * Add hyperlinks to table
     *
     * @param array $tableContent Data of table
     * @param array $hyperlinks   Hyperlinks data of table
     *
     * @return mixed
     */
    public function changeHyperlinksTable($tableContent, $hyperlinks)
    {
        if (isset($tableContent['style']) && is_string($tableContent['style'])) {
            $tableContent['style'] = json_decode($tableContent['style'], false);
        }

        $count = count($tableContent['style']);
        if ($count < 1) {
            $tableContent['style']        = new stdClass();
            $tableContent['style']->table = new stdClass();
            $tableContent['style']->rows  = new stdClass();
            $tableContent['style']->cols  = new stdClass();
            $tableContent['style']->cells = new stdClass();
        } else {
            if ($tableContent['style']->cells === null) {
                $tableContent['style']->cells = new stdClass();
            }
        }

        $cells = new stdClass();

        foreach ($hyperlinks as $key => $hyperlink) {
            $keyArray                    = explode('!', $key);
            $cells->{$key}               = array(
                0 => (int) $keyArray[0],
                1 => (int) $keyArray[1]
            );
            $cells->{$key}[2]            = new stdClass();
            $cells->{$key}[2]->cell_type = 'html';
            if (isset($tableContent['style']->cells->{$key}[2])) {
                $cells->{$key}[2] = (object) array_merge((array) $tableContent['style']->cells->{$key}[2], (array) $cells->{$key}[2]);
            }
        }
        $tableContent['style']->cells = (object) array_merge((array) $tableContent['style']->cells, (array) $cells);
        $tableContent['style']        = json_encode($tableContent['style']);
        return $tableContent;
    }

    /**
     * Function get Data file .csv From Url
     *
     * @param string $spreadsheet_url Url
     * @param string $original_url    Original url
     *
     * @return string|boolean
     */
    public function getCsvDataFromUrl($spreadsheet_url, $original_url)
    {
        $spreadsheet_data = array();
        $csvFile = $this->downloadFileExcel($spreadsheet_url);
        $handle           = fopen($csvFile, 'r');

        if ($handle !== false) {
            //phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found -- fgetcsv return array, can not assign a value to $data before while
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $data               = str_replace("\n", '<br/>', $data);
                $spreadsheet_data[] = $data;
            }
            fclose($handle);
            unlink($csvFile);

            $spreadsheet_url = str_replace('output=csv', 'output=xlsx', $spreadsheet_url);
            $file            = $this->getFilexlsFromgg($spreadsheet_url);

            /*if cann't get file from gg (first time)*/
            if ($file === false) {
                $url_query = parse_url($original_url, PHP_URL_QUERY);
                $url_arr   = explode('/', $original_url);
                array_pop($url_arr);
                $csv_url = implode('/', $url_arr);

                if (!empty($url_query)) {
                    parse_str($url_query, $url_query_params);
                    if (isset($url_query_params['gid'])) {
                        $csv_url .= '/export?format=xlsx&id=' . $url_query_params['gid'];
                    }
                }
                $file = $this->getFilexlsFromgg($csv_url);
            }
            return $file;
        } else {
            return false;
        }
    }

    /**
     * Function get file xls from spreadsheet
     *
     * @param string $url_arr Url
     *
     * @return boolean|string
     */
    public function getFilexlsFromgg($url_arr)
    {
        $datafile = $this->getDataFromUrl($url_arr);
        if ($datafile === null) {
            return false;
        }
        $upload_dir = wp_upload_dir();
        $targetPath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
        if (file_exists($targetPath)) {
            $targetFile = $targetPath . 'getfilexlsfromgg.xlsx';
        } else {
            return false;
        }
        $file = fopen($targetFile, 'cw');
        fwrite($file, $datafile);
        fclose($file);
        return $targetFile;
    }

    /**
     * Funciton get Hyperlink from file xls
     * getSheet(0) will get a sheet first
     *
     * @param string  $targetFile Url file
     * @param integer $check      Check delete file xls
     *
     * @return array|boolean
     */
    public function getHyperlinkFromgg($targetFile, $check)
    {
        $xr = new stdClass();
        $xr = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($targetFile);
        $xr->setReadDataOnly(false);
        $objPHPExcel = $xr->load($targetFile);

        $worksheet = $objPHPExcel->getSheet(0);
        $sheet       = $objPHPExcel->getActiveSheet();
        $maxCell     = $sheet->getHighestRowAndColumn();
        $maxColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxCell['column']);

        $styletable = array();
        $pattern    = '@^=(HYPERLINK)\\((.*?)\\)$@';
        $pattern2   = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
        for ($ri = 1; $ri <= $maxCell['row']; $ri ++) {
            for ($ci = 0; $ci < $maxColIndex; $ci ++) {
                $cell = $worksheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci) . $ri)->getValue();
                if (preg_match($pattern, $cell, $matches)) {
                    $cells = explode(',', $matches[2]);
                    preg_match_all($pattern2, $cells[0], $val0);
                    $styletable[($ri - 1) . '!' . ($ci - 1)]['hyperlink'] = $val0[0][0];
                    $styletable[($ri - 1) . '!' . ($ci - 1)]['text']      = preg_replace('/"/', '', $cells[1]);
                }
            }
        }
        if ($check === 1) {
            unlink($targetFile);
        }

        return $styletable;
    }

    /**
     * Function download file
     *
     * @param string $url Url
     *
     * @return boolean|string
     */
    public function downloadFileExcel($url)
    {
        //check file extension
        $ext     = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        $newname = uniqid() . '.' . $ext;
        if (strpos($url, 'docs.google.com/spreadsheet') !== false) {
            $newname = uniqid() . '.csv';
        } elseif (!in_array($ext, $this->allowed_ext)) {
            $this->error_message = __('Wrong file extension', 'wptm');
            return false;
        }

        $upload_dir = wp_upload_dir();

        $targetPath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
            $data = '<html><body bgcolor="#FFFFFF"></body></html>';
            $file = fopen($targetPath . 'index.html', 'w');
            fwrite($file, $data);
            fclose($file);
        }
        $targetFile = $targetPath . $newname;
        $data       = $this->getDataFromUrl($url);
        $file       = fopen($targetFile, 'w');
        fwrite($file, $data);
        fclose($file);

        return $targetFile;
    }

    /**
     * Function get data from url
     *
     * @param string $url Url
     *
     * @return mixed|null
     * @throws Exception Error when get data
     */
    public function getDataFromUrl($url)
    {
        $ch      = curl_init();
        $timeout = 10;
        $agent   = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_REFERER, site_url());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        if (curl_error($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new Exception($error);
        }
        $info = curl_getinfo($ch);
        curl_close($ch);
        $statusCode = (string) $info['http_code'];
        if ($statusCode[0] === '2') {
            return $data;
        } else {
            return null;
        }
    }

    /**
     * Function csvToArray
     *
     * @param string $csv Cvs
     *
     * @return array
     */
    public function csvToArray($csv)
    {
        $arr   = array();
        $lines = explode("\n", $csv);
        foreach ($lines as $row) {
            $row   = str_replace('""', '\\"', $row);
            $arr[] = str_getcsv($row, ',');
        }

        return $arr;
    }
}
