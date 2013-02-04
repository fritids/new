<?php
/**
 * Creates a custom test PDF report
 * @author David Bergmann
 * @since 2013-01-05
 */

require_once('../../bootstrap.php');
require_once('../../lib/tcpdf/config/lang/eng.php');
require_once('../../lib/tcpdf/tcpdf.php');

global $wpdb;
$current_user = wp_get_current_user();

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo-edge.png';
        $this->Image($image_file, 10, 10, 55, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', '', 14);
		// Set text color
		$this->setColor('text',54,111,144);
        // Title
        $this->Cell(0, 15, 'Score Report', 0, false, 'R', 0, '', 0, false, 'C', 'C');
		// Separator
		$this->Line(10, 24, 200, 24, array("width"=>1, "color"=>array(237,116,57)));
		$this->Line(10, 24.8, 200, 24.8, array("width"=>0.3, "color"=>array(192,168,156)));
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('THE EDGE');
$pdf->SetTitle('Report');

//set margins
$pdf->SetMargins(10, 28, 10, true);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

/************************
	Question Types
 ***********************/
$question_types = array();
$question_types['SAT']['Math'] = $question_types['ACT']['Math'] = array(
	"%" => array("name"=>"Basic Percent"),
	"1V" => array("name"=>"1-variable equation"),
	"3D" => array("name"=>"3-D Geo"),
	"A" => array("name"=>"Angles"),
	"AB" => array("name"=>"Absolute Value"),
	"AG" => array("name"=>"Average Table"),
	"AP" => array("name"=>"Advanced Percent"),
	"AS" => array("name"=>"Area/Shaded Area"),
	"AT" => array("name"=>"Algebra Translation"),
	"AV" => array("name"=>"Basic Average"),
	"BD" => array("name"=>"Binomial Squares/Difference of 2 Squares"),
	"BF" => array("name"=>"Basic Fractions"),
	"BP" => array("name"=>"Basic Probability"),
	"C" => array("name"=>"Circles"),
	"CG" => array("name"=>"Coordinate Geo"),
	"CM" => array("name"=>"Combinations"),
	"CR" => array("name"=>"Combined Ratio"),
	"F" => array("name"=>"Functions"),
	"FF" => array("name"=>"FOIL/Factor"),
	"FS" => array("name"=>"Functions w/ Symbols"),
	"GI" => array("name"=>"Graphing Inequalities"),
	"GM" => array("name"=>"Graph Movement"),
	"LG" => array("name"=>"Logic"),
	"LL" => array("name"=>"Lines/Linear Functions"),
	"LO" => array("name"=>"Logs"),
	"LS" => array("name"=>"Lengths, Perimeter, Slope"),
	"MX" => array("name"=>"Matrix"),
	"NT" => array("name"=>"Number Theory"),
	"P" => array("name"=>"Parabolas"),
	"PM" => array("name"=>"Permutations"),
	"PN" => array("name"=>"Pick a Number, Any Number"),
	"PO" => array("name"=>"Polygon"),
	"PP" => array("name"=>"Percent Pie"),
	"PT" => array("name"=>"Pictograph"),
	"RA" => array("name"=>"Rate"),
	"RE" => array("name"=>"Remainder"),
	"RR" => array("name"=>"Related Ratio"),
	"RT" => array("name"=>"Basic Ratio"),
	"SD" => array("name"=>"Solving Directly for the Expression"),
	"SL" => array("name"=>"Slope"),
	"SQ" => array("name"=>"Sequences"),
	"ST" => array("name"=>"Basic Statistics"),
	"T" => array("name"=>"Triangles"),
	"TG" => array("name"=>"Trig Graph"),
	"TO" => array("name"=>"Trigonometry"),
	"TR" => array("name"=>"Transformations"),
	"UW" => array("name"=>"Use Whatcha Got"),
	"VN" => array("name"=>"Venn Diagram"),
	"VS" => array("name"=>"Visual Perception"),
	"XP" => array("name"=>"Exponents"),
);
$question_types['SAT']['Reading'] = array(
	"DP" => array("name"=>"Double Passage"),
	"DS" => array("name"=>"Double Blank Sentence Completion"),
	"EX" => array("name"=>"Explicit"),
	"IF" => array("name"=>"Inferential"),
	"LP" => array("name"=>"Long Passage"),
	"SC" => array("name"=>"Single Blank Sentence Completion"),
	"SP" => array("name"=>"Short Passage"),
	"VC" => array("name"=>"Vocab-in-context"),
);
$question_types['SAT']['Writing'] = $question_types['ACT']['English'] = array(
	"AA" => array("name"=>"Adjective v. Adverb"),
	"AN" => array("name"=>"Analysis"),
	"BC" => array("name"=>"Starting a Sentence w/ Because"),
	"CE" => array("name"=>"Concise Expression"),
	"CP" => array("name"=>"Conjunction Pairs"),
	"DE" => array("name"=>"Descriptors"),
	"DI" => array("name"=>"Diction"),
	"DM" => array("name"=>"Dangling Modifier"),
	"ED" => array("name"=>"Editing"),
	"FR" => array("name"=>"Fragment"),
	"ID" => array("name"=>"Idiomatic Expression"),
	"MS" => array("name"=>"Missing Subject"),
	"NE" => array("name"=>"No Error"),
	"NN" => array("name"=>"Noun Number"),
	"PA" => array("name"=>"Pronoun Agreement"),
	"PC" => array("name"=>"Parallelism Comparison"),
	"PI" => array("name"=>"Paragraph Improvement"),
	"PL" => array("name"=>"Parallelism List"),
	"PR" => array("name"=>"Pronoun Case"),
	"PS" => array("name"=>"Pronoun Shift"),
	"PU" => array("name"=>"Punctuation Error"),
	"PV" => array("name"=>"Passive Verb"),
	"RD" => array("name"=>"Redundant"),
	"RO" => array("name"=>"Run-on"),
	"SU" => array("name"=>"Superlative"),
	"SV" => array("name"=>"Subject-Verb Agreement"),
	"TW" => array("name"=>"Trigger Words"),
	"VP" => array("name"=>"Vague Pronoun"),
	"VT" => array("name"=>"Verb Tense"),
);
$question_types['ACT']['Reading'] = array(
	"H" => array("name"=>"Humanities Passage"),
	"NS" => array("name"=>"Natural Science Passage"),
	"PF" => array("name"=>"Prose Fiction"),
	"SS" => array("name"=>"Social Science Passage"),
);
$question_types['ACT']['Reading'] = array(
	"CV" => array("name"=>"Conflicting Viewpoints"),
	"DR" => array("name"=>"Data Representation"),
	"RS" => array("name"=>"Research Summaries"),
	"SX" => array("name"=>"Science"),
);

/************************
	Get the test info
 ***********************/

$test_exists = false;

if(isset($_GET['id'])) {
	$test_id = $_GET['id'];
	
	$query = "SELECT `id`,`name`,`type` FROM ".ECP_MCT_TABLE_TESTS." WHERE `id`=%d";
	$test = $wpdb->get_row($wpdb->prepare($query, $test_id));
	
	if($test) {
		$test_exists = true;
		
		// Verify if test is completed
		$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_SECTIONS." `sections`
			LEFT JOIN ".ECP_MCT_TABLE_USER_ANSWERS." `answers` ON `answers`.`section_id` = `sections`.`id` AND `answers`.`user_id` = %d
			WHERE `sections`.`test_id`=%d
			AND `answers`.`end_time` IS NULL";
		$is_completed = $wpdb->get_row($wpdb->prepare($query, $current_user->ID, $test->id));
		
		if(!$is_completed->count) {
			// Get notes
			$notes = array();
			$query = "SELECT `notes` FROM ".ECP_MCT_TABLE_USER_NOTES." WHERE `test_id`=%d AND `user_id`=%d";
			$r = $wpdb->get_row($wpdb->prepare($query, $test->id, $current_user->ID));
			$notes = json_decode($r->notes, true);
			
			// Get sections and answers
			$query = "SELECT `sections`.`id` as `id`, `sections`.`type` as `type`, `sections`.`name` as `name`, `answers`.`answers` as `answers` FROM ".ECP_MCT_TABLE_SECTIONS." `sections`
			LEFT JOIN ".ECP_MCT_TABLE_USER_ANSWERS." `answers` ON `answers`.`section_id` = `sections`.`id` AND `answers`.`user_id` = %d
			WHERE `sections`.`test_id`=%d";
			$sections = $wpdb->get_results($wpdb->prepare($query, $current_user->ID, $test->id));
		}
	}
}

/********************
	The Content
 *******************/

// add a page
$pdf->AddPage();

if($test_exists) {
	// Test Title
	$pdf->SetFont('helvetica', 'B', 16);
	$pdf->setColor('text',237,116,57);
	$pdf->Write(0, $test->name, '', 0, 'L');
	
	if(!$is_completed->count) {
		$css = '
			<style>
				.header {
					color: #FFFFFF;
					background-color: #366F90;
					font-size: 35px;
					font-weight: bold;
				}
				.section-title {
					font-size: 40px;
					font-weight: bold;
				}
				.row-desc {
					font-size: 30px;
					text-align: right;
				}
				.wrong {
					color: red;
					font-weight: bold;
				}
				.even {
					background-color: #DDDDDD;
				}
				.percent-header {
					color: #FFFFFF;
					background-color: #ED7439;
					font-size: 35px;
					font-weight: bold;
					text-align: center;
					width: 560x;
				}
				.percent-section {
					color: #FFFFFF;
					background-color: #366F90;
					font-size: 30px;
					font-weight: bold;
					width: 560px;
				}
				.percent-row-header {
					background-color: #555555;
					color: #FFFFFF;
					font-size: 25px;
					text-align: center;
				}
				.percent-row {
					font-size: 25px;
					text-align: center;
				}
			</style>
		';
		
		if($test->type == "SAT") {
			// Section types
			$section_types = array("Reading","Math","Writing");
			
			// Test results
			$pdf->SetFont('helvetica', '', 12);
			$pdf->setColor('text',191,191,191);
			$pdf->MultiCell(23, 5, 'READING', 0, 'C', 0, 0, 85, 27);
			$pdf->MultiCell(23, 5, 'MATH', 0, 'C', 0, 0, '', '');
			$pdf->MultiCell(23, 5, 'WRITING', 0, 'C', 0, 0, '', '');
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->MultiCell(30, 5, 'TOTAL', 0, 'C', 0, 0, '', '');

			$pdf->SetFont('helvetica', '', 12);
			$pdf->setColor('text',237,116,57);
			$pdf->MultiCell(23, 5, $notes['Reading'], 0, 'C', 0, 0, 85, 32);
			$pdf->MultiCell(23, 5, $notes['Math'], 0, 'C', 0, 0, '', '');
			$pdf->MultiCell(23, 5, $notes['Writing'], 0, 'C', 0, 0, '', '');
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->MultiCell(30, 5, $notes['Reading']+$notes['Math']+$notes['Writing'], 0, 'C', 0, 1, '', '');
		} else {
			
			$section_types = array("English","Math","Reading","Science");
			
			// Test results
			$pdf->SetFont('helvetica', '', 12);
			$pdf->setColor('text',191,191,191);
			$pdf->MultiCell(23, 5, 'ENGLISH', 0, 'C', 0, 0, 75, 27);
			$pdf->MultiCell(23, 5, 'MATH', 0, 'C', 0, 0, '', '');
			$pdf->MultiCell(23, 5, 'READING', 0, 'C', 0, 0, '', '');
			$pdf->MultiCell(23, 5, 'SCIENCE', 0, 'C', 0, 0, '', '');
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->MultiCell(30, 5, 'TOTAL', 0, 'C', 0, 0, '', '');

			$pdf->SetFont('helvetica', '', 12);
			$pdf->setColor('text',237,116,57);
			$pdf->MultiCell(23, 5, $notes['English'], 0, 'C', 0, 0, 75, 32);
			$pdf->MultiCell(23, 5, $notes['Math'], 0, 'C', 0, 0, '', '');
			$pdf->MultiCell(23, 5, $notes['Reading'], 0, 'C', 0, 0, '', '');
			$pdf->MultiCell(23, 5, $notes['Science'], 0, 'C', 0, 0, '', '');
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->MultiCell(30, 5, ($notes['English']+$notes['Math']+$notes['Reading']+$notes['Science'])/4, 0, 'C', 0, 1, '', '');
		}
		
		$pdf->SetY(50);
		$pdf->setColor('text',150,150,150);
			
		foreach($section_types as $section_type) {
			$i=0;
			$html = '';
			foreach($sections as $section) {
				if($section->type == $section_type) {
					$i++;
					
					// Get section questions
					$query = "SELECT `id`,`type`,`code`,`options` FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE `section_id`=%d ORDER BY `order`";
					
					$all_questions = $wpdb->get_results($wpdb->prepare($query, $section->id));
					$answers = json_decode($section->answers, true);
					
					//Create rows of 25 questions
					$questions_array = array();
					$row = 0;
					foreach($all_questions as $kq=>$question) {
						$questions_array[$row][] = $question;
						if(($kq+1)%25==0) $row++;
					}
					
					$question_num = 1;
					foreach($questions_array as $m=>$questions) {
						if($i==1 && $m == 0)
							$html .= '<table cellpadding="2" border="0"><tr class="header"><th colspan="3">'.$section_type.'</th></tr>';
						elseif($i%2==0 && $m == 0)
							$html .= '<table cellpadding="2" border="0" style="background-color:#eeeeee;">';
						elseif($m == 0)
							$html .= '<table cellpadding="2" border="0">';
						else
							$html .= '<table cellpadding="2" border="0"><tr><td></td></tr>';
					
						if($m == 0)
							$html .= '<tr><td rowspan="4" width="60" align="center" class="section-title">'.$section->name.'</td><td width="65" class="row-desc">QUESTION</td>';
						else
							$html .= '<tr><td rowspan="4" width="60" align="center" class="section-title"></td><td width="65" class="row-desc">QUESTION</td>';
						
						/* Question Row */
						foreach($questions as $k=>$question) { $html .= '<td width="22" align="center" class="row-desc">'.($question_num++).'</td>'; }
						
						/* Code Row */
						$html .= '</tr><tr><td class="row-desc">CODE</td>';
						foreach($questions as $k=>$question) { $html .= '<td width="22" align="center" class="row-desc">'.$question->code.'</td>'; }
						
						/* Key Row */
						$html .= '</tr><tr><td class="row-desc">KEY</td>';
						foreach($questions as $k=>$question) {
							$options = json_decode($question->options, true);
							$letter_array = array("A","B","C","D","E");
							$letter_array_even = array("F","G","H","J","K");
							if($question->type == "Multiple Choice") {
								foreach($options as $j=>$option)
									if($option['correct']) {
										if($test->type == "ACT" && ($k+1)%2 == 0)
											$html .= '<td width="22" align="center" class="row-desc">'.$letter_array_even[$j].'</td>';
										else
											$html .= '<td width="22" align="center" class="row-desc">'.$letter_array[$j].'</td>';
										break;
									}
							} else {
								$answer = $answers[$question->id];
								$number = to_number($answer['field_1_value'].$answer['field_2_value'].$answer['field_3_value'].$answer['field_4_value']);

								if($options[0]['type'] == "Range") {
									$html .= '<td width="22" align="center" class="row-desc">'.$options[0]['start'].'&#62;x&#60;'.$options[0]['end'].'</td>';
								} else {
									$html .= '<td width="22" align="center" class="row-desc">'.$options[0]['field_1'].$options[0]['field_2'].$options[0]['field_3'].$options[0]['field_4'].'</td>';
								}
							}
						}
						
						/* Student Row */
						$html .= '</tr><tr><td class="row-desc">STUDENT</td>';
						foreach($questions as $k=>$question) {
							$options = json_decode($question->options, true);
							$letter_array = array("A","B","C","D","E");
							$letter_array_even = array("F","G","H","J","K");
							
							if(isset($question_types[$test->type][$section_type][$question->code])) {
								$question_types[$test->type][$section_type][$question->code]["total"]+=1;
							}
							
							if($question->type == "Multiple Choice") {
								$answer = $answers[$question->id];
								
								if($options[$answer]['correct']) {
									if(isset($question_types[$test->type][$section_type][$question->code])) {
										$question_types[$test->type][$section_type][$question->code]["correct"]+=1;
									}
									$html .= '<td width="22" align="center" class="row-desc"><img src="../../images/right.png" border="0" height="10" width="10" /></td>';
								} elseif($letter_array[$answer]) {
									if(isset($question_types[$test->type][$section_type][$question->code])) {
										$question_types[$test->type][$section_type][$question->code]["incorrect"]+=1;
									}
									if($test->type == "ACT" && ($k+1)%2 == 0)
										$html .= '<td width="22" align="center" class="row-desc wrong">'.$letter_array_even[$answer].'</td>';
									else
										$html .= '<td width="22" align="center" class="row-desc wrong">'.$letter_array[$answer].'</td>';
								} else {
									$html .= '<td width="22" align="center" class="row-desc wrong">-</td>';
								}
							} else {
								$answer = $answers[$question->id];
								$number = to_number($answer['field_1_value'].$answer['field_2_value'].$answer['field_3_value'].$answer['field_4_value']);

								foreach($options as $option) {
									$correct = false;
									if($option['type'] == "Range") {
										if($number >= (float) $option['start'] && $number <= (float) $option['end']) {
											if(isset($question_types[$test->type][$section_type][$question->code])) {
												$question_types[$test->type][$section_type][$question->code]["correct"]+=1;
											}
											$html .= '<td width="22" align="center" class="row-desc"><img src="../../images/right.png" border="0" height="10" width="10" /></td>';
											$correct = true;
											break;
										}
									} else {
										$a_number = to_number($option['field_1'].$option['field_2'].$option['field_3'].$option['field_4']);

										if($number == $a_number) {
											if(isset($question_types[$test->type][$section_type][$question->code])) {
												$question_types[$test->type][$section_type][$question->code]["correct"]+=1;
											}
											$html .= '<td width="22" align="center" class="row-desc"><img src="../../images/right.png" border="0" height="10" width="10" /></td>';
											$correct = true;
											break;
										}
									}
								}
								if(!$correct) {
									if(isset($question_types[$test->type][$section_type][$question->code])) {
										$question_types[$test->type][$section_type][$question->code]["incorrect"]+=1;
									}
									$html .= '<td width="22" align="center" class="row-desc wrong">'.$answer['field_1_value'].$answer['field_2_value'].$answer['field_3_value'].$answer['field_4_value'].'</td>';
								}
							}
						}
						$html .= '</tr></table>';
					}
				}
			}
			$pdf->writeHTML($css.$html, true, false, true, false, '');
		}
		
		// add a page
		$pdf->AddPage();

		// Write percents table
		$html = '<table cellpadding="2" border="1" style="float: right;"><tr><th colspan="6" class="percent-header">Skills Analysis</th></tr>';
		foreach($section_types as $section_type) {
			$html .= '<tr><th colspan="6" class="percent-section">'.$section_type.'</th></tr>';
			$html .= '<tr class="percent-row-header"><td width="40">Code</td>';
			$html .= '<td width="200" style="text-align: left;">Question Type</td>';
			$html .= '<td width="80">Percent Correct</td>';
			$html .= '<td width="80">Number Correct</td>';
			$html .= '<td width="80">Number Incorrect</td>';
			$html .= '<td width="80">Total Questions</td></tr>';
			foreach($question_types[$test->type][$section_type] as $code=>$code_desc) {
				if($code_desc['total']) {
					$html .= '<tr class="percent-row"><td>'.$code.'</td>';
					$html .= '<td style="text-align: left;">'.$code_desc['name'].'</td>';
					$percent = number_format($code_desc['correct']*100/$code_desc['total'],0);
					$html .= '<td>'.$percent.'%</td>';
					$html .= '<td style="color: #0CB700;">'.$code_desc['correct'].'</td>';
					$html .= '<td style="color: #FF0000;">'.$code_desc['incorrect'].'</td>';
					$html .= '<td>'.$code_desc['total'].'</td></tr>';
				}
			}
		}
		$html .= '</table>';
		$pdf->SetX(25);
		$pdf->writeHTML($css.$html, true, false, true, false, '');
		
	} else {
		$pdf->SetFont('helvetica', 'BI', 16);
		$pdf->setColor('text',0,0,0);
		$pdf->SetY(50);
		$pdf->Write(0, "Sorry, you haven't finished this test.", '', 0, 'C');
	}
} else {
	$pdf->SetFont('helvetica', 'BI', 16);
	$pdf->SetY(50);
	$pdf->Write(0, "Sorry, test not found.", '', 0, 'C');
}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('report.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

function to_number ($str) {
	if(preg_match('/^((?P<whole>\d+)(?=\s))?(\s*)?(?P<numerator>\d+)\/(?P<denominator>\d+)$/', $str, $m)) {
		return $m['whole'] + $m['numerator']/$m['denominator'];
	}
	return (float) $str;
}