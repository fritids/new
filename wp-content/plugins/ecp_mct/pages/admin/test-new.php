<?php 
require(FILE_DIR.'pages/wpframe.php');
wpframe_stop_direct_call(__FILE__);

$action = 'new';
if($_REQUEST['action'] == 'edit') $action = 'edit';

$dtest = array();
$sections = array();
if($action == 'edit') {
	$query = "SELECT `id`, `name`, `options_num` FROM ".ECP_MCT_TABLE_TESTS." WHERE `id`=%d";
	$dtest = $wpdb->get_row($wpdb->prepare($query, $_REQUEST['test']));
	
	// Prepare sections array
	$query = "SELECT `id`, `name`, `duration` FROM ".ECP_MCT_TABLE_SECTIONS." WHERE `test_id`=%d ORDER BY `order`";
	$dsections = $wpdb->get_results($wpdb->prepare($query, $_REQUEST['test']));
	
	foreach($dsections as $k=>$section) {
		$sections[$k]['id'] = $section->id;
		$sections[$k]['name'] = stripslashes($section->name);
		$sections[$k]['duration'] = stripslashes($section->duration);
		$sections[$k]['options_num'] = stripslashes($dtest->options_num);
		$sections[$k]['questions'] = array();
		
		// Get questions
		$query = "SELECT `id`, `type`, `options` FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE `section_id`=%d ORDER BY `order`";
		$dquestions = $wpdb->get_results($wpdb->prepare($query, $section->id));
		
		foreach($dquestions as $j=>$question) {
			$sections[$k]['questions'][$j]['id'] = $question->id;
			$sections[$k]['questions'][$j]['type'] = stripslashes($question->type);
			$sections[$k]['questions'][$j]['options'] = json_decode($question->options, true);
		}
	}
}

if($_REQUEST['message'] == 'new_test') {
	wpframe_message('New test added');
} else if ($_REQUEST['message'] == 'update_test') {
	wpframe_message('Test updated');
}

wp_enqueue_script("jquery");
?>

<script type="text/javascript" src="<?php echo PLUGIN_DIR; ?>js/knockout-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo PLUGIN_DIR; ?>js/jquery.validate.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/admin.css" />

<div class="wrap">
	
	<div id="theme-options-wrap"><img class="icon32" src="<?php echo PLUGIN_DIR; ?>images/icon-32.png"></div>
	<h2 class="page-title">Add New Multiple Choice Test</h2>
	
	<form name="test-new" id="test-new" action="<?php echo PLUGIN_DIR; ?>test-save-action.php" method="post">
		<div class="title-field">
			<input type="text" class="required" name="test_title" autocomplete="off" id="title" placeholder="Enter title here" value="<?php echo stripslashes($dtest->name); ?>">
		</div>
		
		<?php if($action == 'edit'):  ?>
		<div class="inside">
			<div id="edit-slug-box">
				<strong>Permalink:</strong>
				<span id="sample-permalink"><?php echo get_option('home') . '/blog/test/' ?><span id="editable-post-name" title="Click to edit this part of the permalink">test_<?php echo $dtest->id; ?></span>/</span>
				<span id="view-post-btn"><a href="<?php echo get_option('home') . '/blog/test/test_'.$dtest->id ?>" class="button" target="_blank">View Test</a></span>
			</div>
		</div>
		<?php endif; ?>
		
		<div class="field">
			<label>Number of options per question</label>
			<input type="text" class="required number" autocomplete="off" size="2" data-bind="value: options_num, uniqueName: true, enable: sections().length==0">
			<em>(for multiple choice questions)</em>
		</div>
		
		<h3>Test Sections</h3><hr>
		
		<div id="poststuff" data-bind="foreach: sections">
			<div class="postbox">
				<h3 class="hndle">
					<input type="text" class="required name" autocomplete="off" placeholder="Enter section name" data-bind="value: name, uniqueName: true">
					<span class="duration">
						Duration:<input type="text" class="required, number" size="3" autocomplete="off" data-bind="value: duration, uniqueName: true" /><em>(in seconds)</em>
					</span>
					<a data-bind="click: $root.removeSection" class="remove-section">Remove Section</a>
				</h3>
				<div class="inside">
					<ul class="questions-list" data-bind="foreach: questions">
						<li>
							<label class="question-lbl">Question <span data-bind="text: $index()+1"></span> <a data-bind="click: $parent.removeQuestion" class="remove-question">Remove</a></label>
							<div class="field" data-bind="ifnot: type">
								Question Type:
								<select data-bind="options: $root.question_types, value: type, optionsCaption: 'Choose...'"></select>
							</div>
							
							<!-- ko if: type() == "Multiple Choice" -->
							<label>Choose the correct answer(s)</label>
							<div class="options-list-container clearfix">
								<ol class="options-list" data-bind="foreach: options">
									<li><input type="checkbox" data-bind="checked: correct" /></li>
								</ol>
							</div>
							<!-- /ko -->
							
							<!-- ko if: type() == "Fill In" -->
							<label>Add the possible answer(s)</label>
							<div class="answers-list-container">
								<ol class="answers-list" data-bind="foreach: options">
									<li>
										<select data-bind="options: $root.field_1_values, value: field_1"></select>
										<select data-bind="options: $root.field_2_values, value: field_2"></select>
										<select data-bind="options: $root.field_3_values, value: field_3"></select>
										<select data-bind="options: $root.field_4_values, value: field_4"></select>
										<a data-bind="click: $parent.removeAnswer, visible: $index()>0" class="remove-answer">Remove</a>
									</li>
								</ol>
								<a data-bind="click: addAnswer" class="add-answer">Add Answer</a>
							</div>
							<!-- /ko -->
						</li>
					</ul>
					<a data-bind="click: addQuestion" class="add-question">Add Question</a>
				</div>
			</div>
		</div>
		
		<a data-bind="click: $root.addSection" class="add-section">Add Section</a>

		<p class="submit">
			<input type="hidden" name="action" id="action" value="<?php echo $action; ?>" />
			<input type="hidden" name="test_id" value="<?php echo $dtest->id; ?>" />
			<input type="hidden" name="options_num" data-bind="value: $root.options_num" />
			<input type="hidden" name="sections" data-bind="value: ko.toJSON($root.sections)" />
			<input type="hidden" name="deleted_sections" data-bind="value: ko.toJSON($root.deleted_sections)" />
			<input type="submit" name="submit" value="Save" style="font-weight: bold;" tabindex="4" />
		</p>
	</form>
	
	<!--pre data-bind="text: ko.toJSON($data,null,2)"></pre-->
	
</div>




<script type="text/javascript">
	// Class to represent a row in the seat reservations grid
	function Section(data) {
		var self = this;
		self.id = ko.observable(data.id);
		self.name = ko.observable(data.name);
		self.duration = ko.observable(data.duration);
		self.questions = ko.observableArray([]);
		self.deleted_questions = ko.observableArray([]);
		
		// Operations
		self.addQuestion = function() {
			self.questions.push(new Question({options_num: data.options_num}));
		}
		self.removeQuestion = function(question) {
			if(question.id()) {
				self.deleted_questions.push(question.id());
			}
			self.questions.remove(question)
		}
		
		// Load from server
		if(data.questions) {
			var questions = jQuery.map(data.questions, function(item) {
				return new Question(item);
			});
			self.questions(questions);
		}
	}
	
	function Question(data) {
		var self = this;
		self.id = ko.observable(data.id);
		self.options = ko.observableArray([]);
		self.type = ko.observable(data.type);
		
		self.type.subscribe(function() {
			if(self.type() == "Multiple Choice") {
				var ii;
				for(ii=0; ii<data.options_num; ii++) {
					self.options.push(new MC_Option([]));
				}
			} else if(self.type() == "Fill In"){
				self.options.push(new FI_Answer([]));
			}
		});
		
		// Operations
		self.addAnswer = function() {
			self.options.push(new FI_Answer([]));
		}
		self.removeAnswer = function(answer) { self.options.remove(answer) }
		
		// Load from server
		if(data.options) {
			var options = jQuery.map(data.options, function(item) {
				if(self.type() == "Multiple Choice") {
					return new MC_Option(item);
				} else if(self.type() == "Fill In"){
					return new FI_Answer(item);
				}
				return new Question(item);
			});
			self.options(options);
		}
	}
	
	function MC_Option(data) {
		var self = this;
		self.correct = ko.observable(data.correct?data.correct:false);
	}
	
	function FI_Answer(data) {
		var self = this;
		self.field_1 = ko.observable(data.field_1);
		self.field_2 = ko.observable(data.field_2);
		self.field_3 = ko.observable(data.field_3);
		self.field_4 = ko.observable(data.field_4);
	}
	
	function EcpMctViewModel() {
		var self = this;
		
		self.options_num = ko.observable(0);
		self.sections = ko.observableArray([]);
		self.deleted_sections = ko.observableArray([]);
		self.question_types = new Array("Multiple Choice","Fill In");
		self.field_1_values = self.field_4_values = new Array("",".","0","1","2","3","4","5","6","7","8","9");
		self.field_2_values = self.field_3_values = new Array("","/",".","0","1","2","3","4","5","6","7","8","9");

		// Operations
		self.addSection = function() {
			if(self.options_num())
				self.sections.push(new Section({options_num: self.options_num()}));
			else
				alert("Please set the number of options per question");
		}
		self.removeSection = function(section) {
			if(section.id()) {
				self.deleted_sections.push(section.id());
			}
			self.sections.remove(section)
		}
		
		
		// Load initial state from server
		if(jQuery("#action").val() == "edit") {
			self.options_num('<?php echo $dtest->options_num?$dtest->options_num:0 ?>');
			
			var sections = jQuery.map(jQuery.parseJSON('<?php echo json_encode($sections); ?>'), function(item) {
				return new Section(item);
			});
			self.sections(sections);
		}
		
	}
	
	ko.applyBindings(new EcpMctViewModel());
	
	
	
	
	
	
	jQuery(document).ready(function() {
		jQuery("#test-new").validate();
	});
</script>