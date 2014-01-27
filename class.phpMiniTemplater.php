<?

class phpMiniTemplater
{
    
    const PMT_UNDEFINEDTEMPLATE = 0;
    const PMT_FILETEMPLATE      = 1;
    const PMT_INLINETEMPLATE    = 2;
    
    public $aParams = array
    (
        'sTemplate'             => '',
        'sTemplateDir'          => '',
        'sTemplateType'         => self::PMT_UNDEFINEDTEMPLATE
    );
    
    public $aData = array();
    
    function __construct($sTemplateNameParam = '', $sTemplateTypeParam = self::PMT_UNDEFINEDTEMPLATE, $aDataParam = array()){
        
        switch ($sTemplateTypeParam)
        {
            case self::PMT_FILETEMPLATE :
                if (file_exists($sTemplateParam)) {
                    $this->aParams['sTemplate'] = $sTemplateNameParam;
                    $this->aParams['sTemplateType'] = self::PMT_FILETEMPLATE;
                    break;
                    }
            case self::PMT_INLINETEMPLATE : 
                    $this->aParams['sTemplate'] = $sTemplateNameParam;
                    $this->aParams['sTemplateType'] = self::PMT_INLINETEMPLATE;
                    break;
            default : 
                    $this->aParams['sTemplate'] = '';
                    $this->aParams['sTemplateType'] = self::PMT_UNDEFINEDTEMPLATE;
                    break;
        }
            
        $this->aData = $aDataParam;
        }
        
    function Parse(){
        
        $sReadyTemplate = !empty($this->aParam['sTemplateType']) ? $this->sInlineTemplate : file_get_contents($this->sFileTemplate);
        
        if ($sReadyTemplate == '') return $sReadyTemplate;
        foreach ($this->aData as $sKey => $oValue){
            if (is_array($oValue)) {
                $sReadyTemplateInt = '';
                foreach ($oValue as $oValueInt) $sReadyTemplateInt .= $oValueInt->Parse();
                $sReadyTemplate = preg_replace("/\{$sKey\}/",$sReadyTemplateInt,$sReadyTemplate);
                }
            elseif (is_object($oValue)) {
                $sReadyTemplate = preg_replace("/\{$sKey\}/",$oValue->Parse(),$sReadyTemplate);
                }
            else 
                $sReadyTemplate = preg_replace("/\{$sKey\}/",$oValue,$sReadyTemplate);
            }
        return $sReadyTemplate;
        }
    }
?>
