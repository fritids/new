<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" encoding="utf-8" />
    <xsl:template match="/">
		<a>
			<xsl:attribute name="href">
				<xsl:value-of select="$Link" disable-output-escaping="no" />
			</xsl:attribute>
			<img>
				<xsl:attribute name="src">
					<xsl:value-of select="$Slika" disable-output-escaping="no" />
				</xsl:attribute>
			</img>
		</a>
    </xsl:template>
</xsl:stylesheet>