<?php
/**
 * PHPExcel → PhpSpreadsheet compatibility shim for legacy CI controllers.
 * Loaded via Composer autoload files.
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!class_exists('PHPExcel', false)) {
    class PHPExcel extends Spreadsheet
    {
    }
}

if (!class_exists('PHPExcel_IOFactory', false)) {
    class PHPExcel_IOFactory
    {
        private static function mapType($type)
        {
            $map = [
                'Excel2007' => 'Xlsx',
                'Excel5' => 'Xls',
                'CSV' => 'Csv',
                'OOCalc' => 'Ods',
                'Excel2003XML' => 'Xml',
            ];
            return $map[$type] ?? $type;
        }

        public static function load($filename, $flags = 0)
        {
            return IOFactory::load($filename);
        }

        public static function identify($filename)
        {
            return IOFactory::identify($filename);
        }

        public static function createReader($readerType)
        {
            return IOFactory::createReader(self::mapType($readerType));
        }

        public static function createWriter($spreadsheet, $writerType)
        {
            return IOFactory::createWriter($spreadsheet, self::mapType($writerType));
        }

        public static function createReaderForFile($filename)
        {
            return IOFactory::createReaderForFile($filename);
        }
    }
}

if (!class_exists('PHPExcel_Style_Alignment', false)) {
    class PHPExcel_Style_Alignment
    {
        const HORIZONTAL_GENERAL = Alignment::HORIZONTAL_GENERAL;
        const HORIZONTAL_LEFT = Alignment::HORIZONTAL_LEFT;
        const HORIZONTAL_RIGHT = Alignment::HORIZONTAL_RIGHT;
        const HORIZONTAL_CENTER = Alignment::HORIZONTAL_CENTER;
        const HORIZONTAL_CENTER_CONTINUOUS = Alignment::HORIZONTAL_CENTER_CONTINUOUS;
        const HORIZONTAL_JUSTIFY = Alignment::HORIZONTAL_JUSTIFY;
        const VERTICAL_BOTTOM = Alignment::VERTICAL_BOTTOM;
        const VERTICAL_TOP = Alignment::VERTICAL_TOP;
        const VERTICAL_CENTER = Alignment::VERTICAL_CENTER;
        const VERTICAL_JUSTIFY = Alignment::VERTICAL_JUSTIFY;
    }
}

if (!class_exists('PHPExcel_Style_Border', false)) {
    class PHPExcel_Style_Border
    {
        const BORDER_NONE = Border::BORDER_NONE;
        const BORDER_DASHDOT = Border::BORDER_DASHDOT;
        const BORDER_DASHDOTDOT = Border::BORDER_DASHDOTDOT;
        const BORDER_DASHED = Border::BORDER_DASHED;
        const BORDER_DOTTED = Border::BORDER_DOTTED;
        const BORDER_DOUBLE = Border::BORDER_DOUBLE;
        const BORDER_HAIR = Border::BORDER_HAIR;
        const BORDER_MEDIUM = Border::BORDER_MEDIUM;
        const BORDER_MEDIUMDASHDOT = Border::BORDER_MEDIUMDASHDOT;
        const BORDER_MEDIUMDASHDOTDOT = Border::BORDER_MEDIUMDASHDOTDOT;
        const BORDER_MEDIUMDASHED = Border::BORDER_MEDIUMDASHED;
        const BORDER_SLANTDASHDOT = Border::BORDER_SLANTDASHDOT;
        const BORDER_THICK = Border::BORDER_THICK;
        const BORDER_THIN = Border::BORDER_THIN;
    }
}

if (!class_exists('PHPExcel_Style_Fill', false)) {
    class PHPExcel_Style_Fill
    {
        const FILL_NONE = Fill::FILL_NONE;
        const FILL_SOLID = Fill::FILL_SOLID;
        const FILL_GRADIENT_LINEAR = Fill::FILL_GRADIENT_LINEAR;
        const FILL_GRADIENT_PATH = Fill::FILL_GRADIENT_PATH;
        const FILL_PATTERN_DARKDOWN = Fill::FILL_PATTERN_DARKDOWN;
        const FILL_PATTERN_DARKGRAY = Fill::FILL_PATTERN_DARKGRAY;
        const FILL_PATTERN_DARKGRID = Fill::FILL_PATTERN_DARKGRID;
        const FILL_PATTERN_DARKHORIZONTAL = Fill::FILL_PATTERN_DARKHORIZONTAL;
        const FILL_PATTERN_DARKTRELLIS = Fill::FILL_PATTERN_DARKTRELLIS;
        const FILL_PATTERN_DARKUP = Fill::FILL_PATTERN_DARKUP;
        const FILL_PATTERN_DARKVERTICAL = Fill::FILL_PATTERN_DARKVERTICAL;
        const FILL_PATTERN_GRAY0625 = Fill::FILL_PATTERN_GRAY0625;
        const FILL_PATTERN_GRAY125 = Fill::FILL_PATTERN_GRAY125;
        const FILL_PATTERN_LIGHTDOWN = Fill::FILL_PATTERN_LIGHTDOWN;
        const FILL_PATTERN_LIGHTGRAY = Fill::FILL_PATTERN_LIGHTGRAY;
        const FILL_PATTERN_LIGHTGRID = Fill::FILL_PATTERN_LIGHTGRID;
        const FILL_PATTERN_LIGHTHORIZONTAL = Fill::FILL_PATTERN_LIGHTHORIZONTAL;
        const FILL_PATTERN_LIGHTTRELLIS = Fill::FILL_PATTERN_LIGHTTRELLIS;
        const FILL_PATTERN_LIGHTUP = Fill::FILL_PATTERN_LIGHTUP;
        const FILL_PATTERN_LIGHTVERTICAL = Fill::FILL_PATTERN_LIGHTVERTICAL;
        const FILL_PATTERN_MEDIUMGRAY = Fill::FILL_PATTERN_MEDIUMGRAY;
    }
}

if (!class_exists('PHPExcel_Style_NumberFormat', false)) {
    class PHPExcel_Style_NumberFormat
    {
        const FORMAT_GENERAL = NumberFormat::FORMAT_GENERAL;
        const FORMAT_TEXT = NumberFormat::FORMAT_TEXT;
        const FORMAT_NUMBER = NumberFormat::FORMAT_NUMBER;
        const FORMAT_NUMBER_00 = NumberFormat::FORMAT_NUMBER_00;
        const FORMAT_NUMBER_COMMA_SEPARATED1 = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1;
        const FORMAT_NUMBER_COMMA_SEPARATED2 = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
        const FORMAT_PERCENTAGE = NumberFormat::FORMAT_PERCENTAGE;
        const FORMAT_PERCENTAGE_00 = NumberFormat::FORMAT_PERCENTAGE_00;
        const FORMAT_DATE_YYYYMMDD = NumberFormat::FORMAT_DATE_YYYYMMDD;
        const FORMAT_DATE_DDMMYYYY = NumberFormat::FORMAT_DATE_DDMMYYYY;
        const FORMAT_DATE_DMYSLASH = NumberFormat::FORMAT_DATE_DMYSLASH;
        const FORMAT_DATE_DMYMINUS = NumberFormat::FORMAT_DATE_DMYMINUS;
        const FORMAT_DATE_DMMINUS = NumberFormat::FORMAT_DATE_DMMINUS;
        const FORMAT_DATE_MYMINUS = NumberFormat::FORMAT_DATE_MYMINUS;
        const FORMAT_DATE_XLSX14 = NumberFormat::FORMAT_DATE_XLSX14;
        const FORMAT_DATE_XLSX15 = NumberFormat::FORMAT_DATE_XLSX15;
        const FORMAT_DATE_XLSX16 = NumberFormat::FORMAT_DATE_XLSX16;
        const FORMAT_DATE_XLSX17 = NumberFormat::FORMAT_DATE_XLSX17;
        const FORMAT_DATE_XLSX22 = NumberFormat::FORMAT_DATE_XLSX22;
        const FORMAT_DATE_DATETIME = NumberFormat::FORMAT_DATE_DATETIME;
        const FORMAT_DATE_TIME1 = NumberFormat::FORMAT_DATE_TIME1;
        const FORMAT_DATE_TIME2 = NumberFormat::FORMAT_DATE_TIME2;
        const FORMAT_DATE_TIME3 = NumberFormat::FORMAT_DATE_TIME3;
        const FORMAT_DATE_TIME4 = NumberFormat::FORMAT_DATE_TIME4;
        const FORMAT_DATE_TIME5 = NumberFormat::FORMAT_DATE_TIME5;
        const FORMAT_DATE_TIME6 = NumberFormat::FORMAT_DATE_TIME6;
        const FORMAT_DATE_TIME7 = NumberFormat::FORMAT_DATE_TIME7;
        const FORMAT_DATE_TIME8 = NumberFormat::FORMAT_DATE_TIME8;
        const FORMAT_DATE_YYYYMMDDSLASH = NumberFormat::FORMAT_DATE_YYYYMMDDSLASH;
        const FORMAT_CURRENCY_USD_SIMPLE = NumberFormat::FORMAT_CURRENCY_USD_SIMPLE;
        const FORMAT_CURRENCY_USD = NumberFormat::FORMAT_CURRENCY_USD;
        const FORMAT_CURRENCY_EUR_SIMPLE = NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE;

        public static function toFormattedString($value, $format, $dateTimeCallback = null)
        {
            return NumberFormat::toFormattedString($value, $format, $dateTimeCallback);
        }
    }
}

if (!class_exists('PHPExcel_Worksheet_Drawing', false)) {
    class PHPExcel_Worksheet_Drawing extends Drawing
    {
    }
}

if (!class_exists('PHPExcel_Writer_Excel2007', false)) {
    class PHPExcel_Writer_Excel2007 extends Xlsx
    {
        public function __construct($spreadsheet)
        {
            parent::__construct($spreadsheet);
        }
    }
}

if (!class_exists('PHPExcel_Shared_Date', false)) {
    class PHPExcel_Shared_Date
    {
        public static function ExcelToPHP($dateValue = 0, $adjustToTimezone = false, $timezone = null)
        {
            return Date::excelToTimestamp($dateValue, $timezone);
        }

        public static function PHPToExcel($dateValue = 0, $adjustToTimezone = false, $timezone = null)
        {
            return Date::PHPToExcel($dateValue);
        }

        public static function isDateTime($cell)
        {
            return Date::isDateTime($cell);
        }

        public static function ExcelToPHPObject($dateValue = 0)
        {
            return Date::excelToDateTimeObject($dateValue);
        }
    }
}
