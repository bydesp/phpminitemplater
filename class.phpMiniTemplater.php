<?php
/*
* This file is part of phpMiniTemplater
* (c) 2013 Anton Dvornikov
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
 
class phpMiniTemplater
{
    
    const PMT_UNDEFINEDTEMPLATE = 0;
    const PMT_FILETEMPLATE = 1;
    const PMT_INLINETEMPLATE = 2;
    
    public $aParams = array
    (
        'sTemplate' => '',
        'iTemplateType' => self::PMT_UNDEFINEDTEMPLATE
    );
    
    public $xData = array();
    
/**
* Creates a phpMiniTempater object
*
* @author Anton Dvornikov
*
* @param string $sTemplateNameParam Name of template
* @param mixed $xDataParam variable with template parameters
* @param int $iTemplateTypeParam Type of template
*/
    function __construct($sTemplateNameParam = '', $xDataParam = array(), $iTemplateTypeParam = self::PMT_UNDEFINEDTEMPLATE)
    {
        switch ($iTemplateTypeParam)
        {
            case self::PMT_FILETEMPLATE :
                if (file_exists($sTemplateParam))
                    $this->aParams['iTemplateType'] = self::PMT_FILETEMPLATE;
                else
                    $this->aParams['iTemplateType'] = self::PMT_UNDEFINEDTEMPLATE;
                break;
            case self::PMT_INLINETEMPLATE :
                $this->aParams['iTemplateType'] = self::PMT_INLINETEMPLATE;
                break;
            case self::PMT_UNDEFINEDTEMPLATE :
            default :
                $this->aParams['iTemplateType'] = self::PMT_UNDEFINEDTEMPLATE;
                break;
        }
        $this->aParams['sTemplate'] = $sTemplateNameParam;
        $this->xData = $xDataParam;
    }
    
/**
* Parse phpminitemplater object
*/
    function Parse()
    {
        switch ($this->aParams['iTemplateType'])
        {
            case self::PMT_UNDEFINEDTEMPLATE :
            case self::PMT_FILETEMPLATE :
                if (file_exists($this->aParams['sTemplate']))
                {
                    $sReadyTemplate = file_get_contents($this->aParams['sTemplate']);
                    break;
                }
                else
                    $this->aParams['iTemplateType'] = self::PMT_INLINETEMPLATE;
            case self::PMT_INLINETEMPLATE :
            default :
                $sReadyTemplate = $this->aParams['sTemplate'];
                break;
        }
        
        if ($sReadyTemplate == '')
            return $sReadyTemplate;
            
        foreach ($this->xData as $sKey => $oValue)
        {
            if (is_array($oValue))
            {
                $sReadyTemplateInternal = '';
                foreach ($oValue as $oValueInternal)
                    $sReadyTemplateInternal .= $oValueInternal->Parse();
                $sReadyTemplate = preg_replace("/\{$sKey\}/",$sReadyTemplateInternal,$sReadyTemplate);
            }
            elseif (is_object($oValue))
                $sReadyTemplate = preg_replace("/\{$sKey\}/",$oValue->Parse(),$sReadyTemplate);
            else
                $sReadyTemplate = preg_replace("/\{$sKey\}/",$oValue,$sReadyTemplate);
        }
        
        return $sReadyTemplate;
    }
}
?>
