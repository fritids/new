<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" encoding="utf-8" />
    <xsl:template match="/">
		<h2>
			<xsl:value-of select="$TestimonialTitle" />
		</h2>
		<img>
			<xsl:attribute name="src">
				<xsl:value-of select="$TestimonialImage" disable-output-escaping="no" />
			</xsl:attribute>
		</img>
    </xsl:template>
</xsl:stylesheet>