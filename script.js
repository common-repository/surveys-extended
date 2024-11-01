var survey_current_question = 1;
var survey_questions_per_page = 1;
var survey_total_questions = 0;
var survey_current_page = 1;

function previousQuestion(e) {
        //console.log('(previousQuestion) 1');
	if(survey_questions_per_page != 0) return previousPage(e); // Multi question per page 
	var answered = false;

        //console.log('(previousQuestion) 2');
	
	jQuery("#question-" + survey_current_question + " .answer").each(function(i) {
		if(this.checked) {
			answered = true;
			return true;
		}
	});
	if(!answered) {
		if(!confirm("You did not select any answer. Are you sure you want to continue?")) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	}
	
	jQuery("#question-" + survey_current_question).hide();
	survey_current_question--;
	jQuery("#question-" + survey_current_question).show();
	
        jQuery("#survey-previous-question").removeAttr('disabled');
        jQuery("#survey-next-question").removeAttr('disabled');
	if(survey_current_question <= 0) {
	        jQuery("#survey-previous-question").attr('disabled', true);
		jQuery("#survey-action-button").show();
	}
}

function nextQuestion(e) {
        //console.log('(nextQuestion) 1');
	if(survey_questions_per_page != 0) return nextPage(e); // Multi question per page 
	var answered = false;
	
        //console.log('(nextQuestion) 2');
	
	jQuery("#question-" + survey_current_question + " .answer").each(function(i) {
		if(this.checked) {
			answered = true;
			return true;
		}
	});
	if(!answered) {
		if(!confirm("You did not select any answer. Are you sure you want to continue?")) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	}
	
	jQuery("#question-" + survey_current_question).hide();
	survey_current_question++;
	jQuery("#question-" + survey_current_question).show();
	
        jQuery("#survey-previous-question").removeAttr('disabled');
        jQuery("#survey-next-question").removeAttr('disabled');
	if(survey_total_questions <= survey_current_question) {
	        jQuery("#survey-next-question").attr('disabled', true);
		jQuery("#survey-action-button").show();
	}
}

function previousPage(e) {

        //console.log('(previousPage)');

	survey_current_page--;
	showPreviousXQuestions();
}


function nextPage(e) {

        //console.log('(nextPage)');

	survey_current_page++;
	showNextXQuestions();
}

function showPreviousXQuestions() {

        //console.log('(showPreviousXQuestions)');

	jQuery(".survey-question").hide();
	
	var from_question = ((survey_current_page - 1) * survey_questions_per_page) + 1;
	var to_question = survey_current_page * survey_questions_per_page;
	for(var i = from_question; i <= to_question; i++) {
		jQuery("#question-" + i).show();
	}
	
        jQuery("#survey-previous-question").removeAttr('disabled');
        jQuery("#survey-next-question").removeAttr('disabled');
	if(from_question <= 1 ) {
		jQuery("#survey-action-button").show();
	        jQuery("#survey-previous-question").attr('disabled', true);
	}
}

function showNextXQuestions() {

        //console.log('(nextPreviousXQuestions)');

	jQuery(".survey-question").hide();
	
	var from_question = ((survey_current_page - 1) * survey_questions_per_page) + 1;
	var to_question = survey_current_page * survey_questions_per_page;
	for(var i = from_question; i <= to_question; i++) {
		jQuery("#question-" + i).show();
	}
	
        jQuery("#survey-previous-question").removeAttr('disabled');
        jQuery("#survey-next-question").removeAttr('disabled');
	if(to_question >= survey_total_questions) {
		jQuery("#survey-action-button").show();
	        jQuery("#survey-next-question").attr('disabled', true);
	}
}

function surveyInit() {

        //console.log('(surveyInit)');

	survey_total_questions = jQuery(".survey-question").length;
	if(survey_questions_per_page > 1) {
                //console.log('(surveyInit) if');
		jQuery("#survey-action-button").hide();
		jQuery("#survey-previous-question").hide();
		jQuery("#survey-next-question").show();
		showNextXQuestions();
	
	} else if(survey_questions_per_page == 0) { //Single page mode.
                //console.log('(surveyInit) else if');
		jQuery(".survey-question").show();
		jQuery("#survey-action-button").show();
		jQuery("#survey-previous-question").hide();
		jQuery("#survey-next-question").hide();
	
	} else {
                //console.log('(surveyInit) else');
		jQuery("#question-1").show();
	        jQuery("#survey-previous-question").show();
	        jQuery("#survey-next-question").show();

	        jQuery("#survey-previous-question").attr('disabled', true);
                if (survey_total_questions <=1 ) {
	            jQuery("#survey-next-question").attr('disabled', true);
                }
	}
	
	jQuery("#survey-previous-question").click(previousQuestion);
	jQuery("#survey-next-question").click(nextQuestion);
}

jQuery(document).ready(surveyInit); 
