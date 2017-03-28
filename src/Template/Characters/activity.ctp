<?php
use App\View\AppView;

/* @var AppView $this */
/* @var array $data */
/* @var array $data2 */
$this->set('title_for_layout', 'Character Login Activity');

?>
<h3>Inactive Players</h3>
<table>
    <tr>
        <th> Character Name</th>
        <th> Year</th>
        <th> Month</th>
        <th> Logins</th>
    </tr>
    <?php foreach($data as $row): ?>
        <tr>
            <td><?php echo $row['character_name']; ?></td>
            <td><?php echo $row['year']; ?></td>
            <td><?php echo $row['month']; ?></td>
            <td><?php echo $row['total']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<h3>All Character Logins (this year)</h3>
<table>
    <tr>
        <th> Character Name</th>
        <th> Year</th>
        <th> Month</th>
        <th> Logins</th>
    </tr>
    <?php foreach($data2 as $row): ?>
        <tr>
            <td><?php echo $row['character_name']; ?></td>
            <td><?php echo $row['year']; ?></td>
            <td><?php echo $row['month']; ?></td>
            <td><?php echo $row['total']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<!--<div style="width:80%; margin: 0 auto;">-->
<!--    <div>-->
<!--        <canvas id="canvas" height="450" width="600"></canvas>-->
<!--    </div>-->
<!--</div>-->
<?php echo $this->Html->script('chart/Chart.min'); ?>
<script>
    $(function() {
//        createChart();
//        setInterval(createChart, 2000);
    });

    function createChart() {
        var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
        var lineChartData = {
            labels : ["January","February","March","April","May","June","July"],
            datasets : [
                {
                    label: "My First dataset",
                    fillColor : "rgba(220,220,220,0.2)",
                    strokeColor : "rgba(220,220,220,1)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(220,220,220,1)",
                    data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                },
                {
                    label: "My Second dataset",
                    fillColor : "rgba(151,187,205,0.2)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(151,187,205,1)",
                    data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                }
            ]

        };

        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });
    }
</script>
