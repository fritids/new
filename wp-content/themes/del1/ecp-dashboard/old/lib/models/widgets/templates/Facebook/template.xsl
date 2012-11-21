<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"  xmlns:fb="#default">
	<xsl:output method="html" encoding="utf-8" />
    <xsl:template match="/">
		<div class="facebookWiget">
	        <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US"></script>
	        <script type="text/javascript">FB.init("");</script>
	        <fb:fan stream="" connections="10" width="300" height="243">
	        	<xsl:attribute name="profile_id">
					<xsl:value-of select="$ID" disable-output-escaping="no" />
				</xsl:attribute>
	        </fb:fan>
        </div>
    </xsl:template>
</xsl:stylesheet>