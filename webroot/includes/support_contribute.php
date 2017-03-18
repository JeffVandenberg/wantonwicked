<?php
$page_title = $contentHeader = 'Become a Supporter';

ob_start();
?>

<div class="paragraph">
    Here are the options right now. You can purchase for multiple months, it's just a bit of work.
</div>
<table>
    <tr>
        <th>
            Level
        </th>
        <th>
            Description
        </th>
        <th>

        </th>
    </tr>
    <tr style="vertical-align: top;">
        <td>
            $5 Supporter
        </td>
        <td>
            Support for 1 month (bonus xp covered for 1 character)
        </td>
        <td>
            <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="AAFEH2J33XUTC">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </td>
    </tr>
    <tr style="vertical-align: top;">
        <td>
            $10 Supporter
        </td>
        <td>
            Support for 1 month (bonus XP covered for 2 characters)
        </td>
        <td>
            <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="YJDRTH8BGZR7W">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </td>
    </tr>
    <tr style="vertical-align: top;">
        <td>
            $50
        </td>
        <td>
            Support for 1 year (Bonus for 1 character)
        </td>
        <td>
            <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="SAHP2ZA72TT4N">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </td>
    </tr>
    <tr style="vertical-align: top;">
        <td>
            $100
        </td>
        <td>
            Support for 1 year (Bonus for 2 character)
        </td>
        <td>
            <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="6HWVLSE6L94A2">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>
            <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" >
                <input type="hidden" name="cmd" value="_cart">
                <input type="hidden" name="business" value="5J7FLA8X2FQAU">
                <input type="hidden" name="display" value="1">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_viewcart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </td>
    </tr>
</table>

<?php
$page_content = ob_get_clean();