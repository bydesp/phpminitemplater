<?
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
    
    public $aData = array();
    
/**
* Creates a phpMiniTempater object
*
* @author Anton Dvornikov
*
* @param string $sTemplateNameParam Name of template
* @param integer $iTemplateTypeParam Type of template
* @param array $aDataParam Array with template parameters
*/
    function __construct($sTemplateNameParam = '', $iTemplateTypeParam = self::PMT_UNDEFINEDTEMPLATE, $aDataParam = array())
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
        $this->aData = $aDataParam;
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
            
        foreach ($this->aData as $sKey => $oValue)
        {
            if (is_array($oValue))
            {
                $sReadyTemplateInternal = '';
                foreach ($oValue as $oValueInt)
                    $sReadyTemplateInternal .= $oValueInt->Parse();
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
