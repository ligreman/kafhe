<html>
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<table cellspacing="0" cellpadding="10" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
	<tbody>
		<tr>
            <td style="background-color:#00924a;font-size:22px;text-align: left;">
                <?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/kafhe3.png'); ?>
            </td>
		</tr>
		<tr>
            <td style="color:#363636;font-size:16px;padding-top:5px;">
            	<?php if(isset($data['description'])) echo $data['description'];  ?>
            </td>
		</tr>
		<tr>
            <td>
				<?php echo $content ?>
            </td>
		</tr>
        <tr>
            <td>
                <p>No lo pienses mas y entra directamente a <?php Chtml::link('kafhe',Yii::app()->getBaseUrl(true));?></p>
            </td>
        </tr>
	</tbody>
</table>
</body>
</html>