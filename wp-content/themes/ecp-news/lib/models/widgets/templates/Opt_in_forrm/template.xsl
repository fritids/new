<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"  xmlns:fb="#default">
	<xsl:output method="html" encoding="utf-8" />
    <xsl:template match="/">
		<div class="form-holder">
			<img>
				<xsl:attribute name="src">
					<xsl:value-of select="$form_ico" disable-output-escaping="no" />
				</xsl:attribute>
			</img>
			<form action="" id="guideForm">
				<div class="alignleft-label">
					<label>Name:</label>
					<input id="guide_name" type="text" />
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div class="alignleft-label">
					<label>Email:</label>
					<input id="guide_email" type="text" />
					<div class="clear"></div>
				</div>
				<input type="submit" id="submit_guide" value="Email me the Guide" class="button green right button-costume" />
			</form>
			<div class="clear"></div>
			<div id="notification_guide"></div>
		</div>
    </xsl:template>
</xsl:stylesheet>