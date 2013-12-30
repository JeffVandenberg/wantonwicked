<?php /* @var View $this */ ?>
<?php /* @var array $admins */ ?>
<?php /* @var array $sts */ ?>
<?php /* @var array $assts */ ?>
<?php /* @var array $wikis */ ?>
<?php $this->set('title_for_layout', "Storytellers"); ?>

<div style="width: 50%;float: left;">
    <h2>
        Administrators
    </h2>
    <table>
        <tr>
            <th>
                Name
            </th>
        </tr>
        <?php foreach($admins as $admin): ?>
            <tr>
                <td>
                    <?php echo $admin['User']['Name']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div style="width: 50%;float: left;">
    <h2>
        Wiki Staff
    </h2>
    <table>
        <tr>
            <th>
                Name
            </th>
        </tr>
        <?php foreach($wikis as $admin): ?>
            <tr>
                <td>
                    <?php echo $admin['U']['Name']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div style="clear:both;"></div>
<h2>
    Storytellers
</h2>
<table>
    <tr>
        <th>
            Name
        </th>
        <th>
            Groups
        </th>
    </tr>
    <?php foreach($sts as $user): ?>
        <tr>
            <td>
                <?php echo $user['U']['Name']; ?>
            </td>
            <td>
                <?php echo $user[0]['groups']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>
    Asst STs/Narrators
</h2>
<table>
    <tr>
        <th>
            Name
        </th>
        <th>
            Groups
        </th>
    </tr>
    <?php foreach($assts as $user): ?>
        <tr>
            <td>
                <?php echo $user['U']['Name']; ?>
            </td>
            <td>
                <?php echo $user[0]['groups']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

