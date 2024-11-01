<?php
include('wpframe.php');
wpframe_stop_direct_call(__FILE__);

$colors = array('bf1a1d', 'bfa024', '830088', '3e4cb9', '52803b', '805b6f', '827f71', '4bc0e0', 'c12a8f', '000000');
$color_index = 0;
function nextColor() {
	global $colors, $color_index;
	
	$color_index++;
	if($color_index > count($colors)) $color_index = 0;
	
	return "#" . $colors[$color_index - 1];
}

$survey_id = $_REQUEST['survey'];
$survey_details = $wpdb->get_row($wpdb->prepare("SELECT ID, name FROM {$wpdb->prefix}surveys_extended_survey WHERE ID=%d", $survey_id));
?>

<div class="wrap">
<h2><?php print t("Survey '") . $survey_details->name . t("' Responses"); ?></h2>
<p><a href="edit.php?page=surveys-extended/individual_responses.php&amp;survey=<?php echo $survey_id ?>">Show Individual Responses</a></p>

<?php
$questions = $wpdb->get_results($wpdb->prepare("SELECT ID,question FROM {$wpdb->prefix}surveys_extended_question WHERE survey_ID=%d", $survey_id));

foreach($questions as $question) {
print $question->question;

//Show result.
$answers = $wpdb->get_results($wpdb->prepare("SELECT A.ID, A.answer, (SELECT COUNT(*) FROM {$wpdb->prefix}surveys_extended_result_answer WHERE answer_ID=A.ID) AS votes 
	FROM {$wpdb->prefix}surveys_extended_answer AS A WHERE A.question_ID=%d ORDER BY A.sort_order", $question->ID));

if(count($answers)) {
?>
<table class="widefat">
	<thead>
	<tr>
		<th scope="col"><?php e('Answer') ?></th>
		<th scope="col" width="200"><?php e('Votes') ?></th>
		<th scope="col" width="150"><?php e('Vote Count/Percentage') ?></th>
	</tr>
	</thead>
	<tbody id="the-list">
<?php
//First find the total number of votes
$total = 0;
foreach($answers as $ans) $total += $ans->votes;
$class = 'alternate';

// Show each answer with the number of votes it recived.
foreach($answers as $ans) {
	$class = ('alternate' == $class) ? '' : 'alternate';
	print "<tr class='$class'><td>";
	if(isset($user_answer) and $ans->ID == $user_answer) print "<strong>{$ans->answer}</strong>"; //Users answer.
	else print stripslashes($ans->answer);
	print "</td>";
	
	if($ans->votes == 0) $percent = 0;
	else $percent = intval(($ans->votes / $total) * 100);
	$color = nextColor();
	print "<td class='pollin-result-bar-holder' style='width:200px;'><div class='pollin-result-bar' style='background-color:$color; width:$percent%;'>&nbsp;</div></td>";
	print "<td>{$ans->votes} Votes($percent%)</td>";
	print "</tr>";
}
?></tbody>
</table>
<strong>Total Votes: <?=$total?></strong><br /><hr />

<?php } else { ?>
<p>User inputed answers. Use the <a href="edit.php?page=surveys-extended/individual_responses.php&amp;survey=<?php echo $survey_id ?>">Individual Responses</a> section to see the answers to this question.</p>
<hr />
<?php }

} ?>

<a href="edit.php?page=surveys-extended/survey.php">Manage Surveys</a>
</div>