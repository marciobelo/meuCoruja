<?php

/* * *****************************************************************************
 * FPDF                                                                         *
 *                                                                              *
 * Version: 1.0                                                                 *
 * Date:    2010-07-10                                                          *
 * Author:  Marcelo Atie                                                        *
 * ***************************************************************************** */

/*
 * Classe que extende o FPDF para adicionar recursos extras
 */

require_once 'fpdf.php';

define('FPDF_VERSION', '1.6');

class FpdfCoruja extends FPDF {

    function FpdfCoruja($orientation='P', $unit='mm', $format='A4') {

        $this->FPDF($orientation, $unit, $format);
    }

    function ImageFromString($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='') {
        //Put an image on the page
        if (!isset($this->images[$file])) {
            //First use of this image, get info
            //a imagem nao vem com path, logo, nao adianta ver a extensao

            if ($type == '') {
                $pos = strrpos($file, '.');
                if (!$pos)
                    $this->Error('Image file has no extension and no type was specified: ' . $file);
                $type = substr($file, $pos + 1);
            }


            $type = strtolower($type);
            if ($type == 'jpeg')
                $type = 'jpg';
            //transforma $mtd no método _parsepng()
            $mtd = '_get' . $type;
            if (!method_exists($this, $mtd))
                $this->Error('Unsupported image type: ' . $type);
            $info = $this->$mtd($file);
            $info['i'] = count($this->images) + 1;
            $this->images[$file] = $info;
        }
        else
            $info=$this->images[$file];
        //Automatic width and height calculation if needed
        if ($w == 0 && $h == 0) {
            //Put image at 72 dpi
            $w = $info['w'] / $this->k;
            $h = $info['h'] / $this->k;
        } elseif ($w == 0)
            $w = $h * $info['w'] / $info['h'];
        elseif ($h == 0)
            $h = $w * $info['h'] / $info['w'];
        //Flowing mode
        if ($y === null) {
            if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
                //Automatic page break
                $x2 = $this->x;
                $this->AddPage($this->CurOrientation, $this->CurPageFormat);
                $this->x = $x2;
            }
            $y = $this->y;
            $this->y+=$h;
        }
        if ($x === null)
            $x = $this->x;
        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q', $w * $this->k, $h * $this->k, $x * $this->k, ($this->h - ($y + $h)) * $this->k, $info['i']));
        if ($link)
            $this->Link($x, $y, $w, $h, $link);
    }

    function _getpng($file) {
        //Extract info from a PNG file
        //$f=fopen($file,'rb');

        $fiveMBs = 5 * 1024 * 1024;
        $f = fopen("php://temp/maxmemory:$fiveMBs", 'r+');

        fputs($f, $file);

        rewind($f);

        //$f=fopen($file,'rb');

        if (!$f)
            $this->Error('Can\'t open image file: ' . $file);
        //Check signature
        if ($this->_readstream($f, 8) != chr(137) . 'PNG' . chr(13) . chr(10) . chr(26) . chr(10))
            $this->Error('Not a PNG file: ' . $file);
        //Read header chunk
        $this->_readstream($f, 4);
        if ($this->_readstream($f, 4) != 'IHDR')
            $this->Error('Incorrect PNG file: ' . $file);
        $w = $this->_readint($f);
        $h = $this->_readint($f);
        $bpc = ord($this->_readstream($f, 1));
        if ($bpc > 8)
            $this->Error('16-bit depth not supported: ' . $file);
        $ct = ord($this->_readstream($f, 1));
        if ($ct == 0)
            $colspace = 'DeviceGray';
        elseif ($ct == 2)
            $colspace = 'DeviceRGB';
        elseif ($ct == 3)
            $colspace = 'Indexed';
        else
            $this->Error('Alpha channel not supported: ' . $file);
        if (ord($this->_readstream($f, 1)) != 0)
            $this->Error('Unknown compression method: ' . $file);
        if (ord($this->_readstream($f, 1)) != 0)
            $this->Error('Unknown filter method: ' . $file);
        if (ord($this->_readstream($f, 1)) != 0)
            $this->Error('Interlacing not supported: ' . $file);
        $this->_readstream($f, 4);
        $parms = '/DecodeParms <</Predictor 15 /Colors ' . ($ct == 2 ? 3 : 1) . ' /BitsPerComponent ' . $bpc . ' /Columns ' . $w . '>>';
        //Scan chunks looking for palette, transparency and image data
        $pal = '';
        $trns = '';
        $data = '';
        do {
            $n = $this->_readint($f);
            $type = $this->_readstream($f, 4);
            if ($type == 'PLTE') {
                //Read palette
                $pal = $this->_readstream($f, $n);
                $this->_readstream($f, 4);
            } elseif ($type == 'tRNS') {
                //Read transparency info
                $t = $this->_readstream($f, $n);
                if ($ct == 0)
                    $trns = array(ord(substr($t, 1, 1)));
                elseif ($ct == 2)
                    $trns = array(ord(substr($t, 1, 1)), ord(substr($t, 3, 1)), ord(substr($t, 5, 1)));
                else {
                    $pos = strpos($t, chr(0));
                    if ($pos !== false)
                        $trns = array($pos);
                }
                $this->_readstream($f, 4);
            }
            elseif ($type == 'IDAT') {
                //Read image data block
                $data.=$this->_readstream($f, $n);
                $this->_readstream($f, 4);
            } elseif ($type == 'IEND')
                break;
            else
                $this->_readstream($f, $n + 4);
        }
        while ($n);
        if ($colspace == 'Indexed' && empty($pal))
            $this->Error('Missing palette in ' . $file);
        //fclose($f);
        $this->ff = $f;
        return array('w' => $w, 'h' => $h, 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'FlateDecode', 'parms' => $parms, 'pal' => $pal, 'trns' => $trns, 'data' => $data);
    }

}
?>
