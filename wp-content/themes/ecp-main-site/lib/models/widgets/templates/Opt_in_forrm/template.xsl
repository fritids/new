<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
 <xsl:output method="xml"  version="1.0"  encoding="UTF-8"  indent="no"  omit-xml-declaration="yes"  />
    <xsl:template match="/">
		<div class="form-holder">
			<img alt="form_icon" style="padding-left:15px;margin-bottom:5px;">
				<xsl:attribute name="src">
					<xsl:value-of select="$form_ico" disable-output-escaping="no" />
				</xsl:attribute>
			</img>
			<form action="" id="guideForm">
				<div class="alignleft-label">
					<label>Name:</label>
					<input id="guide_name" type="text" />
					<div class="clear">&#160;</div>
				</div>
				<div class="clear">&#160;</div>
				<div class="alignleft-label">
					<label>Email:</label>
					<input id="guide_email" type="text" />
					<div class="clear">&#160;</div>
				</div>
				<input type="submit" id="submit_guide" value="Email me the Guide" class="button green right button-costume" />
			</form>
			<div class="clear">&#160;</div>
			<div id="notification_guide">&#160;</div>
		</div>
    </xsl:template>
</xsl:stylesheet>