<?php wp_enqueue_script("jquery"); ?>
<script type="text/javascript" src="<?php echo PLUGIN_DIR; ?>js/knockout-2.2.0.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/admin.css" />

<div class="wrap">
	<div id="theme-options-wrap"><img class="icon32" src="<?php echo PLUGIN_DIR; ?>images/icon-32.png"></div>
	<h2 class="page-title">Add New Multiple Choice Test</h2>
	

	
	<form name="test-new" action="<?php echo PLUGIN_DIR; ?>test-save-action.php" method="post" id="post">
		<div class="title-field">
			<input type="text" name="test_title" autocomplete="off" id="title" placeholder="Enter title here" value="">
		</div>
		
		<div class="field">
			<label># questions per section</label>
			<input type="text" autocomplete="off" size="2" data-bind="value: questions_num, enable: sections().length==0">
		</div>
		
		<div class="field">
			<label># options per question</label>
			<input type="text" autocomplete="off" size="2" data-bind="value: options_num, enable: sections().length==0">
		</div>
		
		<h3>Test Sections</h3><hr>
		
		<div id="poststuff" data-bind="foreach: sections">
			<div class="postbox">
				<h3 class="hndle">
					<input type="text" autocomplete="off" placeholder="Enter section name" data-bind="value: name">
					<a class="remove-section" data-bind="click: $root.removeSection">Remove Section</a>
				</h3>
				<div class="inside">
					<h4>Choose the correct answer(s)</h4>
					<ul class="questions-list" data-bind="foreach: questions">
						<li>
							<label>Question <span data-bind="text: order+1"></span></label>
							<ol class="options-list" data-bind="foreach: options">
								<li><input type="checkbox" data-bind="checked: correct" /></li>
							</ol>
						</li>
					</ul>
				</div>
			</div>
		</div>
		
		<a data-bind="click: $root.addSection">Add Section</a>

		<p class="submit">
			<input type="hidden" name="questions_num" data-bind="value: $root.questions_num" />
			<input type="hidden" name="options_num" data-bind="value: $root.options_num" />
			<input type="hidden" name="sections" data-bind="value: ko.toJSON($root.sections)" />
			<input type="submit" name="submit" value="Save" style="font-weight: bold;" tabindex="4" />
		</p>
	</form>
	
</div>




<script type="text/javascript">
	// Class to represent a row in the seat reservations grid
	function Section(data) {
		var self = this;
		self.order = data.order;
		self.name = ko.observable(data.name);
		self.questions = ko.observableArray([]);
		
		var i;
		for(i=0; i<data.questions_num; i++) {
			self.questions.push(new Question({order: self.questions().length, options_num: data.options_num}));
		}
	}
	
	function Question(data) {
		var self = this;
		self.order = data.order;
		self.options = ko.observableArray([]);
		
		var i;
		for(i=0; i<data.options_num; i++) {
			self.options.push(new Option({order: self.options().length}));
		}
	}
	
	function Option(data) {
		var self = this;
		self.order = data.order;
		self.correct = ko.observable(data.correct?data.correct:false);
	}
	
	function EcpMctViewModel() {
		var self = this;
		
		self.questions_num = ko.observable(0);
		self.options_num = ko.observable(0);
		self.sections = ko.observableArray([]);
		
		
		
		// Operations
		self.addSection = function() {
			self.sections.push(new Section({order: self.sections().length, questions_num: self.questions_num(), options_num: self.options_num()}));
		}
		self.removeSection = function(section) { self.sections.remove(section) }
	}
	
	ko.applyBindings(new EcpMctViewModel());
	
	jQuery(document).ready(function() {
		
	});
</script>