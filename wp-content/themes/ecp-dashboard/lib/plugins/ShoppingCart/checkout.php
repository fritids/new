<?php get_header(); ?>
<div id="content">
	<h2>Checkout</h2>
	

		<fieldset class="account_creation">
			<h3>Your personal information</h3>
			<p class="radio required">
				<span>Title</span>
				<input type="radio" name="id_gender" id="id_gender1" value="1">
				<label for="id_gender1" class="top">Mr.</label>
				<input type="radio" name="id_gender" id="id_gender2" value="2">
				<label for="id_gender2" class="top">Ms.</label>
			</p>
			<p class="required text">
				<label for="customer_firstname">First name</label>
				<input onkeyup="$('#firstname').val(this.value);" type="text" class="text" id="customer_firstname" name="customer_firstname" value="">
				<sup>*</sup>
			</p>
			<p class="required text">
				<label for="customer_lastname">Last name</label>
				<input onkeyup="$('#lastname').val(this.value);" type="text" class="text" id="customer_lastname" name="customer_lastname" value="">
				<sup>*</sup>
			</p>
			<p class="required text">
				<label for="email">E-mail</label>
				<input type="text" class="text" id="email" name="email" value="nik_raver@yahoo.com">
				<sup>*</sup>
			</p>
			<p class="required password">
				<label for="passwd">Password</label>
				<input type="password" class="text" name="passwd" id="passwd">
				<sup>*</sup>
				<span class="form_info">(5 characters min.)</span>
			</p>
			<p class="select">
				<span>Birthday</span>
				<select id="days" name="days">
					<option value="">-</option>
											<option value="1">1&nbsp;&nbsp;</option>
											<option value="2">2&nbsp;&nbsp;</option>
											<option value="3">3&nbsp;&nbsp;</option>
											<option value="4">4&nbsp;&nbsp;</option>
											<option value="5">5&nbsp;&nbsp;</option>
											<option value="6">6&nbsp;&nbsp;</option>
											<option value="7">7&nbsp;&nbsp;</option>
											<option value="8">8&nbsp;&nbsp;</option>
											<option value="9">9&nbsp;&nbsp;</option>
											<option value="10">10&nbsp;&nbsp;</option>
											<option value="11">11&nbsp;&nbsp;</option>
											<option value="12">12&nbsp;&nbsp;</option>
											<option value="13">13&nbsp;&nbsp;</option>
											<option value="14">14&nbsp;&nbsp;</option>
											<option value="15">15&nbsp;&nbsp;</option>
											<option value="16">16&nbsp;&nbsp;</option>
											<option value="17">17&nbsp;&nbsp;</option>
											<option value="18">18&nbsp;&nbsp;</option>
											<option value="19">19&nbsp;&nbsp;</option>
											<option value="20">20&nbsp;&nbsp;</option>
											<option value="21">21&nbsp;&nbsp;</option>
											<option value="22">22&nbsp;&nbsp;</option>
											<option value="23">23&nbsp;&nbsp;</option>
											<option value="24">24&nbsp;&nbsp;</option>
											<option value="25">25&nbsp;&nbsp;</option>
											<option value="26">26&nbsp;&nbsp;</option>
											<option value="27">27&nbsp;&nbsp;</option>
											<option value="28">28&nbsp;&nbsp;</option>
											<option value="29">29&nbsp;&nbsp;</option>
											<option value="30">30&nbsp;&nbsp;</option>
											<option value="31">31&nbsp;&nbsp;</option>
									</select>
								<select id="months" name="months">
					<option value="">-</option>
											<option value="1">January&nbsp;</option>
											<option value="2">February&nbsp;</option>
											<option value="3">March&nbsp;</option>
											<option value="4">April&nbsp;</option>
											<option value="5">May&nbsp;</option>
											<option value="6">June&nbsp;</option>
											<option value="7">July&nbsp;</option>
											<option value="8">August&nbsp;</option>
											<option value="9">September&nbsp;</option>
											<option value="10">October&nbsp;</option>
											<option value="11">November&nbsp;</option>
											<option value="12">December&nbsp;</option>
									</select>
				<select id="years" name="years">
					<option value="">-</option>
											<option value="2000">2000&nbsp;&nbsp;</option>
											<option value="1999">1999&nbsp;&nbsp;</option>
											<option value="1998">1998&nbsp;&nbsp;</option>
											<option value="1997">1997&nbsp;&nbsp;</option>
											<option value="1996">1996&nbsp;&nbsp;</option>
											<option value="1995">1995&nbsp;&nbsp;</option>
											<option value="1994">1994&nbsp;&nbsp;</option>
											<option value="1993">1993&nbsp;&nbsp;</option>
											<option value="1992">1992&nbsp;&nbsp;</option>
											<option value="1991">1991&nbsp;&nbsp;</option>
											<option value="1990">1990&nbsp;&nbsp;</option>
											<option value="1989">1989&nbsp;&nbsp;</option>
											<option value="1988">1988&nbsp;&nbsp;</option>
											<option value="1987">1987&nbsp;&nbsp;</option>
											<option value="1986">1986&nbsp;&nbsp;</option>
											<option value="1985">1985&nbsp;&nbsp;</option>
											<option value="1984">1984&nbsp;&nbsp;</option>
											<option value="1983">1983&nbsp;&nbsp;</option>
											<option value="1982">1982&nbsp;&nbsp;</option>
											<option value="1981">1981&nbsp;&nbsp;</option>
											<option value="1980">1980&nbsp;&nbsp;</option>
											<option value="1979">1979&nbsp;&nbsp;</option>
											<option value="1978">1978&nbsp;&nbsp;</option>
											<option value="1977">1977&nbsp;&nbsp;</option>
											<option value="1976">1976&nbsp;&nbsp;</option>
											<option value="1975">1975&nbsp;&nbsp;</option>
											<option value="1974">1974&nbsp;&nbsp;</option>
											<option value="1973">1973&nbsp;&nbsp;</option>
											<option value="1972">1972&nbsp;&nbsp;</option>
											<option value="1971">1971&nbsp;&nbsp;</option>
											<option value="1970">1970&nbsp;&nbsp;</option>
											<option value="1969">1969&nbsp;&nbsp;</option>
											<option value="1968">1968&nbsp;&nbsp;</option>
											<option value="1967">1967&nbsp;&nbsp;</option>
											<option value="1966">1966&nbsp;&nbsp;</option>
											<option value="1965">1965&nbsp;&nbsp;</option>
											<option value="1964">1964&nbsp;&nbsp;</option>
											<option value="1963">1963&nbsp;&nbsp;</option>
											<option value="1962">1962&nbsp;&nbsp;</option>
											<option value="1961">1961&nbsp;&nbsp;</option>
											<option value="1960">1960&nbsp;&nbsp;</option>
											<option value="1959">1959&nbsp;&nbsp;</option>
											<option value="1958">1958&nbsp;&nbsp;</option>
											<option value="1957">1957&nbsp;&nbsp;</option>
											<option value="1956">1956&nbsp;&nbsp;</option>
											<option value="1955">1955&nbsp;&nbsp;</option>
											<option value="1954">1954&nbsp;&nbsp;</option>
											<option value="1953">1953&nbsp;&nbsp;</option>
											<option value="1952">1952&nbsp;&nbsp;</option>
											<option value="1951">1951&nbsp;&nbsp;</option>
											<option value="1950">1950&nbsp;&nbsp;</option>
											<option value="1949">1949&nbsp;&nbsp;</option>
											<option value="1948">1948&nbsp;&nbsp;</option>
											<option value="1947">1947&nbsp;&nbsp;</option>
											<option value="1946">1946&nbsp;&nbsp;</option>
											<option value="1945">1945&nbsp;&nbsp;</option>
											<option value="1944">1944&nbsp;&nbsp;</option>
											<option value="1943">1943&nbsp;&nbsp;</option>
											<option value="1942">1942&nbsp;&nbsp;</option>
											<option value="1941">1941&nbsp;&nbsp;</option>
											<option value="1940">1940&nbsp;&nbsp;</option>
											<option value="1939">1939&nbsp;&nbsp;</option>
											<option value="1938">1938&nbsp;&nbsp;</option>
											<option value="1937">1937&nbsp;&nbsp;</option>
											<option value="1936">1936&nbsp;&nbsp;</option>
											<option value="1935">1935&nbsp;&nbsp;</option>
											<option value="1934">1934&nbsp;&nbsp;</option>
											<option value="1933">1933&nbsp;&nbsp;</option>
											<option value="1932">1932&nbsp;&nbsp;</option>
											<option value="1931">1931&nbsp;&nbsp;</option>
											<option value="1930">1930&nbsp;&nbsp;</option>
											<option value="1929">1929&nbsp;&nbsp;</option>
											<option value="1928">1928&nbsp;&nbsp;</option>
											<option value="1927">1927&nbsp;&nbsp;</option>
											<option value="1926">1926&nbsp;&nbsp;</option>
											<option value="1925">1925&nbsp;&nbsp;</option>
											<option value="1924">1924&nbsp;&nbsp;</option>
											<option value="1923">1923&nbsp;&nbsp;</option>
											<option value="1922">1922&nbsp;&nbsp;</option>
											<option value="1921">1921&nbsp;&nbsp;</option>
											<option value="1920">1920&nbsp;&nbsp;</option>
											<option value="1919">1919&nbsp;&nbsp;</option>
											<option value="1918">1918&nbsp;&nbsp;</option>
											<option value="1917">1917&nbsp;&nbsp;</option>
											<option value="1916">1916&nbsp;&nbsp;</option>
											<option value="1915">1915&nbsp;&nbsp;</option>
											<option value="1914">1914&nbsp;&nbsp;</option>
											<option value="1913">1913&nbsp;&nbsp;</option>
											<option value="1912">1912&nbsp;&nbsp;</option>
											<option value="1911">1911&nbsp;&nbsp;</option>
											<option value="1910">1910&nbsp;&nbsp;</option>
											<option value="1909">1909&nbsp;&nbsp;</option>
											<option value="1908">1908&nbsp;&nbsp;</option>
											<option value="1907">1907&nbsp;&nbsp;</option>
											<option value="1906">1906&nbsp;&nbsp;</option>
											<option value="1905">1905&nbsp;&nbsp;</option>
											<option value="1904">1904&nbsp;&nbsp;</option>
											<option value="1903">1903&nbsp;&nbsp;</option>
											<option value="1902">1902&nbsp;&nbsp;</option>
											<option value="1901">1901&nbsp;&nbsp;</option>
											<option value="1900">1900&nbsp;&nbsp;</option>
									</select>
			</p>
			<p class="checkbox">
				<input type="checkbox" name="newsletter" id="newsletter" value="1">
				<label for="newsletter">Sign up for our newsletter</label>
			</p>
			<p class="checkbox">
				<input type="checkbox" name="optin" id="optin" value="1">
				<label for="optin">Receive special offers from our partners</label>
			</p>
		</fieldset>
		<fieldset class="account_creation">
			<h3>Your address</h3>
			<p class="text">
				<label for="company">Company</label>
				<input type="text" class="text" id="company" name="company" value="">
			</p>
			<p class="required text">
				<label for="firstname">First name</label>
				<input type="text" class="text" id="firstname" name="firstname" value="">
				<sup>*</sup>
			</p>
			<p class="required text">
				<label for="lastname">Last name</label>
				<input type="text" class="text" id="lastname" name="lastname" value="">
				<sup>*</sup>
			</p>
			<p class="required text">
				<label for="address1">Address</label>
				<input type="text" class="text" name="address1" id="address1" value="">
				<sup>*</sup>
			</p>
			<p class="text">
				<label for="address2">Address (2)</label>
				<input type="text" class="text" name="address2" id="address2" value="">
			</p>
			<p class="required text">
				<label for="postcode">Postal code / Zip code</label>
				<input type="text" class="text" name="postcode" id="postcode" value="">
				<sup>*</sup>
			</p>
			<p class="required text">
				<label for="city">City</label>
				<input type="text" class="text" name="city" id="city" value="">
				<sup>*</sup>
			</p>
			<p class="required select">
				<label for="id_country">Country</label>
				<select name="id_country" id="id_country">
					<option value="">-</option>
										<option value="231">Afghanistan</option>
										<option value="244">Åland Islands</option>
										<option value="230">Albania</option>
										<option value="38">Algeria</option>
										<option value="39">American Samoa</option>
										<option value="40">Andorra</option>
										<option value="41">Angola</option>
										<option value="42">Anguilla</option>
										<option value="232">Antarctica</option>
										<option value="43">Antigua and Barbuda</option>
										<option value="44">Argentina</option>
										<option value="45">Armenia</option>
										<option value="46">Aruba</option>
										<option value="24">Australia</option>
										<option value="2">Austria</option>
										<option value="47">Azerbaijan</option>
										<option value="48">Bahamas</option>
										<option value="49">Bahrain</option>
										<option value="50">Bangladesh</option>
										<option value="51">Barbados</option>
										<option value="52">Belarus</option>
										<option value="3">Belgium</option>
										<option value="53">Belize</option>
										<option value="54">Benin</option>
										<option value="55">Bermuda</option>
										<option value="56">Bhutan</option>
										<option value="34">Bolivia</option>
										<option value="233">Bosnia and Herzegovina</option>
										<option value="57">Botswana</option>
										<option value="234">Bouvet Island</option>
										<option value="58">Brazil</option>
										<option value="235">British Indian Ocean Territory</option>
										<option value="59">Brunei</option>
										<option value="236">Bulgaria</option>
										<option value="60">Burkina Faso</option>
										<option value="61">Burma (Myanmar)</option>
										<option value="62">Burundi</option>
										<option value="63">Cambodia</option>
										<option value="64">Cameroon</option>
										<option value="4">Canada</option>
										<option value="65">Cape Verde</option>
										<option value="237">Cayman Islands</option>
										<option value="66">Central African Republic</option>
										<option value="67">Chad</option>
										<option value="68">Chile</option>
										<option value="5">China</option>
										<option value="238">Christmas Island</option>
										<option value="239">Cocos (Keeling) Islands</option>
										<option value="69">Colombia</option>
										<option value="70">Comoros</option>
										<option value="71">Congo, Dem. Republic</option>
										<option value="72">Congo, Republic</option>
										<option value="240">Cook Islands</option>
										<option value="73">Costa Rica</option>
										<option value="74">Croatia</option>
										<option value="75">Cuba</option>
										<option value="76">Cyprus</option>
										<option value="16">Czech Republic</option>
										<option value="20">Denmark</option>
										<option value="77">Djibouti</option>
										<option value="78">Dominica</option>
										<option value="79">Dominican Republic</option>
										<option value="80">East Timor</option>
										<option value="81">Ecuador</option>
										<option value="82">Egypt</option>
										<option value="83">El Salvador</option>
										<option value="84">Equatorial Guinea</option>
										<option value="85">Eritrea</option>
										<option value="86">Estonia</option>
										<option value="87">Ethiopia</option>
										<option value="88">Falkland Islands</option>
										<option value="89">Faroe Islands</option>
										<option value="90">Fiji</option>
										<option value="7">Finland</option>
										<option value="8" selected="selected">France</option>
										<option value="241">French Guiana</option>
										<option value="242">French Polynesia</option>
										<option value="243">French Southern Territories</option>
										<option value="91">Gabon</option>
										<option value="92">Gambia</option>
										<option value="93">Georgia</option>
										<option value="1">Germany</option>
										<option value="94">Ghana</option>
										<option value="97">Gibraltar</option>
										<option value="9">Greece</option>
										<option value="96">Greenland</option>
										<option value="95">Grenada</option>
										<option value="98">Guadeloupe</option>
										<option value="99">Guam</option>
										<option value="100">Guatemala</option>
										<option value="101">Guernsey</option>
										<option value="102">Guinea</option>
										<option value="103">Guinea-Bissau</option>
										<option value="104">Guyana</option>
										<option value="105">Haiti</option>
										<option value="106">Heard Island and McDonald Islands</option>
										<option value="108">Honduras</option>
										<option value="22">HongKong</option>
										<option value="143">Hungary</option>
										<option value="109">Iceland</option>
										<option value="110">India</option>
										<option value="111">Indonesia</option>
										<option value="112">Iran</option>
										<option value="113">Iraq</option>
										<option value="26">Ireland</option>
										<option value="114">Isle of Man</option>
										<option value="29">Israel</option>
										<option value="10">Italy</option>
										<option value="32">Ivory Coast</option>
										<option value="115">Jamaica</option>
										<option value="11">Japan</option>
										<option value="116">Jersey</option>
										<option value="117">Jordan</option>
										<option value="118">Kazakhstan</option>
										<option value="119">Kenya</option>
										<option value="120">Kiribati</option>
										<option value="121">Korea, Dem. Republic of</option>
										<option value="122">Kuwait</option>
										<option value="123">Kyrgyzstan</option>
										<option value="124">Laos</option>
										<option value="125">Latvia</option>
										<option value="126">Lebanon</option>
										<option value="127">Lesotho</option>
										<option value="128">Liberia</option>
										<option value="129">Libya</option>
										<option value="130">Liechtenstein</option>
										<option value="131">Lithuania</option>
										<option value="12">Luxemburg</option>
										<option value="132">Macau</option>
										<option value="133">Macedonia</option>
										<option value="134">Madagascar</option>
										<option value="135">Malawi</option>
										<option value="136">Malaysia</option>
										<option value="137">Maldives</option>
										<option value="138">Mali</option>
										<option value="139">Malta</option>
										<option value="140">Marshall Islands</option>
										<option value="141">Martinique</option>
										<option value="142">Mauritania</option>
										<option value="35">Mauritius</option>
										<option value="144">Mayotte</option>
										<option value="145">Mexico</option>
										<option value="146">Micronesia</option>
										<option value="147">Moldova</option>
										<option value="148">Monaco</option>
										<option value="149">Mongolia</option>
										<option value="150">Montenegro</option>
										<option value="151">Montserrat</option>
										<option value="152">Morocco</option>
										<option value="153">Mozambique</option>
										<option value="154">Namibia</option>
										<option value="155">Nauru</option>
										<option value="156">Nepal</option>
										<option value="13">Netherlands</option>
										<option value="157">Netherlands Antilles</option>
										<option value="158">New Caledonia</option>
										<option value="27">New Zealand</option>
										<option value="159">Nicaragua</option>
										<option value="160">Niger</option>
										<option value="31">Nigeria</option>
										<option value="161">Niue</option>
										<option value="162">Norfolk Island</option>
										<option value="163">Northern Mariana Islands</option>
										<option value="23">Norway</option>
										<option value="164">Oman</option>
										<option value="165">Pakistan</option>
										<option value="166">Palau</option>
										<option value="167">Palestinian Territories</option>
										<option value="168">Panama</option>
										<option value="169">Papua New Guinea</option>
										<option value="170">Paraguay</option>
										<option value="171">Peru</option>
										<option value="172">Philippines</option>
										<option value="173">Pitcairn</option>
										<option value="14">Poland</option>
										<option value="15">Portugal</option>
										<option value="174">Puerto Rico</option>
										<option value="175">Qatar</option>
										<option value="176">Réunion</option>
										<option value="36">Romania</option>
										<option value="177">Russian Federation</option>
										<option value="178">Rwanda</option>
										<option value="179">Saint Barthélemy</option>
										<option value="180">Saint Kitts and Nevis</option>
										<option value="181">Saint Lucia</option>
										<option value="182">Saint Martin</option>
										<option value="183">Saint Pierre and Miquelon</option>
										<option value="184">Saint Vincent and the Grenadines</option>
										<option value="185">Samoa</option>
										<option value="186">San Marino</option>
										<option value="187">São Tomé and Príncipe</option>
										<option value="188">Saudi Arabia</option>
										<option value="189">Senegal</option>
										<option value="190">Serbia</option>
										<option value="191">Seychelles</option>
										<option value="192">Sierra Leone</option>
										<option value="25">Singapore</option>
										<option value="37">Slovakia</option>
										<option value="193">Slovenia</option>
										<option value="194">Solomon Islands</option>
										<option value="195">Somalia</option>
										<option value="30">South Africa</option>
										<option value="196">South Georgia and the South Sandwich Islands</option>
										<option value="28">South Korea</option>
										<option value="6">Spain</option>
										<option value="197">Sri Lanka</option>
										<option value="198">Sudan</option>
										<option value="199">Suriname</option>
										<option value="200">Svalbard and Jan Mayen</option>
										<option value="201">Swaziland</option>
										<option value="18">Sweden</option>
										<option value="19">Switzerland</option>
										<option value="202">Syria</option>
										<option value="203">Taiwan</option>
										<option value="204">Tajikistan</option>
										<option value="205">Tanzania</option>
										<option value="206">Thailand</option>
										<option value="33">Togo</option>
										<option value="207">Tokelau</option>
										<option value="208">Tonga</option>
										<option value="209">Trinidad and Tobago</option>
										<option value="210">Tunisia</option>
										<option value="211">Turkey</option>
										<option value="212">Turkmenistan</option>
										<option value="213">Turks and Caicos Islands</option>
										<option value="214">Tuvalu</option>
										<option value="215">Uganda</option>
										<option value="216">Ukraine</option>
										<option value="217">United Arab Emirates</option>
										<option value="17">United Kingdom</option>
										<option value="218">Uruguay</option>
										<option value="21">USA</option>
										<option value="219">Uzbekistan</option>
										<option value="220">Vanuatu</option>
										<option value="107">Vatican City State</option>
										<option value="221">Venezuela</option>
										<option value="222">Vietnam</option>
										<option value="223">Virgin Islands (British)</option>
										<option value="224">Virgin Islands (U.S.)</option>
										<option value="225">Wallis and Futuna</option>
										<option value="226">Western Sahara</option>
										<option value="227">Yemen</option>
										<option value="228">Zambia</option>
										<option value="229">Zimbabwe</option>
									</select>
				<sup>*</sup>
			</p>
			<p class="required id_state select" style="display: block; ">
				<label for="id_state">State</label>
				<select name="id_state" id="id_state">
					<option value="">-</option>
				<option value="1">Alabama</option><option value="2">Alaska</option><option value="3">Arizona</option><option value="4">Arkansas</option><option value="5">California</option><option value="6">Colorado</option><option value="7">Connecticut</option><option value="8">Delaware</option><option value="9">Florida</option><option value="10">Georgia</option><option value="11">Hawaii</option><option value="12">Idaho</option><option value="13">Illinois</option><option value="14">Indiana</option><option value="15">Iowa</option><option value="16">Kansas</option><option value="17">Kentucky</option><option value="18">Louisiana</option><option value="19">Maine</option><option value="20">Maryland</option><option value="21">Massachusetts</option><option value="22">Michigan</option><option value="23">Minnesota</option><option value="24">Mississippi</option><option value="25">Missouri</option><option value="26">Montana</option><option value="27">Nebraska</option><option value="28">Nevada</option><option value="29">New Hampshire</option><option value="30">New Jersey</option><option value="31">New Mexico</option><option value="32">New York</option><option value="33">North Carolina</option><option value="34">North Dakota</option><option value="35">Ohio</option><option value="36">Oklahoma</option><option value="37">Oregon</option><option value="38">Pennsylvania</option><option value="39">Rhode Island</option><option value="40">South Carolina</option><option value="41">South Dakota</option><option value="42">Tennessee</option><option value="43">Texas</option><option value="44">Utah</option><option value="45">Vermont</option><option value="46">Virginia</option><option value="47">Washington</option><option value="48">West Virginia</option><option value="49">Wisconsin</option><option value="50">Wyoming</option><option value="51">Puerto Rico</option><option value="52">US Virgin Islands</option></select>
				<sup>*</sup>
			</p>
			<p class="textarea">
				<label for="other">Additional information</label>
				<textarea name="other" id="other" cols="26" rows="3"></textarea>
			</p>
			<p class="text">
				<label for="phone">Home phone</label>
				<input type="text" class="text" name="phone" id="phone" value="">
			</p>
			<p class="text">
				<label for="phone_mobile">Mobile phone</label>
				<input type="text" class="text" name="phone_mobile" id="phone_mobile" value="">
			</p>
			<p class="required text" id="address_alias">
				<label for="alias">Assign an address title for future reference !</label>
				<input type="text" class="text" name="alias" id="alias" value="My address">
				<sup>*</sup>
			</p>
		</fieldset>
		<!-- MODULE ReferralProgram -->
<fieldset class="account_creation">
	<h3>Referral program</h3>
	<p>
		<label for="referralprogram">E-mail address of your sponsor</label>
		<input type="text" size="52" maxlength="128" class="text" id="referralprogram" name="referralprogram" value="">
	</p>
</fieldset>
<!-- END : MODULE ReferralProgram -->
		<p class="cart_navigation required submit">
			<input type="hidden" name="email_create" value="1">
			<input type="hidden" class="hidden" name="back" value="order.php?step=1">			<input type="submit" name="submitAccount" id="submitAccount" value="Register" class="exclusive">
			<span><sup>*</sup>Required field</span>
		</p>


	
</div>
<?php get_footer(); ?>