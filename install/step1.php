<?

/** @global CMain $APPLICATION */
IncludeModuleLangFile(__FILE__);
?>
<form action="<?= $APPLICATION->GetCurPage(); ?>" name="form1">
    <?
    echo bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="hidden" name="id" value="ibs.notebooksstore">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    <p><?
        echo GetMessage("IBS_NS_INSTALL_WARN") ?></p>
    <p><input type="checkbox" name="reset" id="reset" value="Y"><label for="reset"><?
            echo GetMessage("IBS_NS_RESET_DATA") ?></label></p>
    <input type="submit" name="inst" value="<?
    echo GetMessage("MOD_INSTALL"); ?>">
</form>