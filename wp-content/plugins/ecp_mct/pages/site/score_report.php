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
        $image_file = K_PATH_IMAGES.'header-logo.png';
        $this->Image($image_file, 10, 10, '', '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
			</style>
		';
		
		if($test->type == "SAT") {
			
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
			$pdf->MultiCell(30, 5, $notes['English']+$notes['Math']+$notes['Reading']+$notes['Science'], 0, 'C', 0, 1, '', '');
		}
		
		$pdf->SetY(50);
		$pdf->setColor('text',150,150,150);
			
		foreach($section_types as $section_type) {
			$i=0;
			$html = '';
			foreach($sections as $k=>$section) {
				if($section->type == $section_type) {
					$i++;
					if($i==1)
						$html .= '<table cellpadding="2" border="0"><tr class="header"><th colspan="3">'.$section_type.'</th></tr>';
					elseif($i%2==0)
						$html .= '<table cellpadding="2" border="0" style="background-color:#eeeeee"><tr><td></td></tr>';
					else
						$html .= '<table cellpadding="2" border="0"><tr><td></td></tr>';
					// Get section questions
					$query = "SELECT `id`,`type`,`code`,`options` FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE `section_id`=%d ORDER BY `order`";
					
					$questions = $wpdb->get_results($wpdb->prepare($query, $section->id));
					$answers = json_decode($section->answers, true);

					$html .= '<tr><td rowspan="4" width="60" align="center" class="section-title">'.$section->name.'</td><td width="65" class="row-desc">QUESTION</td>';
					
					foreach($questions as $k=>$question) {
						$html .= '<td width="24" align="center" class="row-desc">'.($k+1).'</td>';
					}
					
					$html .= '</tr><tr><td class="row-desc">CODE</td>';
					foreach($questions as $k=>$question) {
						$html .= '<td width="24" align="center" class="row-desc">'.$question->code.'</td>';
					}
					
					
					$html .= '</tr><tr><td class="row-desc">KEY</td>';
					foreach($questions as $k=>$question) {
						$options = json_decode($question->options, true);
						$letter_array = array("A","B","C","D","E");
						$letter_array_even = array("F","G","H","J","K");
						if($question->type == "Multiple Choice") {
							foreach($options as $j=>$option)
								if($option['correct']) {
									if($test->type == "ACT" && ($k+1)%2 == 0)
										$html .= '<td width="24" align="center" class="row-desc">'.$letter_array_even[$j].'</td>';
									else
										$html .= '<td width="24" align="center" class="row-desc">'.$letter_array[$j].'</td>';
									break;
								}
						} else {
							$answer = $answers[$question->id];
							$number = to_number($answer['field_1_value'].$answer['field_2_value'].$answer['field_3_value'].$answer['field_4_value']);
						
							if($options[0]['type'] == "Range") {
								$html .= '<td width="24" align="center" class="row-desc">'.$options[0]['start'].'&#62;x&#60;'.$options[0]['end'].'</td>';
							} else {
								$html .= '<td width="24" align="center" class="row-desc">'.$options[0]['field_1'].$options[0]['field_2'].$options[0]['field_3'].$options[0]['field_4'].'</td>';
							}
						}
					}
					
					
					$html .= '</tr><tr><td class="row-desc">STUDENT</td>';
					foreach($questions as $k=>$question) {
						$options = json_decode($question->options, true);
						$letter_array = array("A","B","C","D","E");
						$letter_array_even = array("F","G","H","J","K");
						if($question->type == "Multiple Choice") {
							$answer = $answers[$question->id];
							if($options[$answer]['correct']) {
								$html .= '<td width="24" align="center" class="row-desc"><img src="../../images/right.png" border="0" height="10" width="10" /></td>';
							} elseif($letter_array[$answer]) {
								if($test->type == "ACT" && ($k+1)%2 == 0)
									$html .= '<td width="24" align="center" class="row-desc wrong">'.$letter_array_even[$answer].'</td>';
								else
									$html .= '<td width="24" align="center" class="row-desc wrong">'.$letter_array[$answer].'</td>';
							} else {
								$html .= '<td width="24" align="center" class="row-desc wrong">-</td>';
							}
						} else {
							$answer = $answers[$question->id];
							$number = to_number($answer['field_1_value'].$answer['field_2_value'].$answer['field_3_value'].$answer['field_4_value']);

							foreach($options as $option) {
								$correct = false;
								if($option['type'] == "Range") {
									if($number >= (float) $option['start'] && $number <= (float) $option['end']) {
										$html .= '<td width="24" align="center" class="row-desc"><img src="../../images/right.png" border="0" height="10" width="10" /></td>';
										$correct = true;
										break;
									}
								} else {
									$a_number = to_number($option['field_1'].$option['field_2'].$option['field_3'].$option['field_4']);

									if($number == $a_number) {
										$html .= '<td width="24" align="center" class="row-desc"><img src="../../images/right.png" border="0" height="10" width="10" /></td>';
										$correct = true;
										break;
									}
								}
							}
							if(!$correct) {
								$html .= '<td width="24" align="center" class="row-desc wrong">'.$answer['field_1_value'].$answer['field_2_value'].$answer['field_3_value'].$answer['field_4_value'].'</td>';
							}
						}
						
					}
					$html .= '</tr></table>';
				}
			}
			
			$pdf->writeHTML($css.$html, true, false, true, false, '');
		}
		
		
		
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