<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/23/14
 * Time: 1:22 PM
 */

$a = 'Hello World!';

$b = array(
    'First',
    'Second',
    'Third'
);

$myUserName = 'jvandenberg';

$userInfo = array(
    'name' => 'Jeff Vandenberg',
    'username' => 'jvandenberg',
    'password' => 'MySecretPass'
);

if($myUserName != $userInfo['username']) {
    $userInfo['password'] = 'Hidden Information';
}

echo 'Show User Information<br />';
foreach($userInfo as $key => $value) {
    echo $key.' : ' . $value . '<br />';
}


function c()
{
    return 'You called me bro!';
}

function d($a = 'Unknown')
{
    return 'Thank you for visiting ' . $a;
}

function e($myParam) {
    return 'Thank you for leaving ' . $a;
}

function add($var1, $var2)
{
    return $var1 + $var2;
}

function formatText($text)
{
    echo '<div>' . $text . '</div>';
}

?>
<pre>
    ECHO A<br />
    <?php echo $a; ?><br />
    Echo B<br />
    <?php echo $b; ?><br />
    Iterate B:<br />
    <?php foreach($b as $key => $value): ?>
        <?php echo $key . ' -> ' . $value; ?><br />
    <?php endforeach; ?>
    Access the first item in B:<br />
    <?php echo $b[0]; ?><br />
    Call C:
    <?php echo c(); ?><br />
    Call D (with parameter)
    <?php echo d('Jill'); ?><br />
    Call D (without parameters)
    <?php echo d(); ?><br />
    Call add (with numbers (1,2))
    <?php echo add(1, 2); ?><br />
    Call Add (with strings (Jeff, Joy))
    <?php echo add('Jeff', 'Joy'); ?><br />
    Formatting Text
    <?php formatText('Hello World!'); ?>
    <?php formatText('Hello World2!'); ?>
    <?php formatText('Hello World3!'); ?>
</pre>