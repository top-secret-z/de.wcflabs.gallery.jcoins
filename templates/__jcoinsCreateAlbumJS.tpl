{*
 * This file is located in the footer, because it must be the last JavaScript which is executed ;
 * because we unbind some buttons
 *}
{if ($templateName == 'imageAdd' || $templateName == 'albumList') && $jcoinsCanCreateAlbum|isset && !$jcoinsCanCreateAlbum}
    <script data-relocate="true">
        $(function() {
            {if $templateName == 'albumList'}
                $("#albumAddButton").unbind().click(function () {
                    $("<div class=\"ajaxMessage jCoinsMessage\"><p>"+ WCF.Language.get("wcf.jcoins.amount.tooLow") +"</p>").wcfDialog({ title: WCF.Language.get('wcf.global.error.title') });
                    return false;
                });
            {elseif $templateName == 'imageAdd'}
                {*
                 * The button is added dynamically from the gallery (after the footer template) and it is very difficult to replace 
                 * the dialog within the JS. So we must make it dirty!
                 *}
                $(document).ready(function () {
                    $(document).find("#albumID").parent().find(".fa-plus").unbind().click(function () {
                        $("<div class=\"ajaxMessage jCoinsMessage\"><p>" + WCF.Language.get("wcf.jcoins.amount.tooLow") + "</p>").wcfDialog({ title: WCF.Language.get('wcf.global.error.title') });
                        return false;
                    });
                });
            {/if}

            WCF.Language.addObject({
                'wcf.jcoins.amount.tooLow': '{lang}wcf.jcoins.amount.tooLow{/lang}'
            });
        });
    </script>
{/if}
