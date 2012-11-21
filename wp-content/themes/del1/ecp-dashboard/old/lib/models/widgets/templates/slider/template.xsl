<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" encoding="utf-8" />
    <xsl:template match="/">
		<h2>
			<xsl:value-of select="$naslov" />
		</h2>
		<a>
			<xsl:attribute name="href">
				<xsl:value-of select="$link" disable-output-escaping="no" />
			</xsl:attribute>
		<img>
			<xsl:attribute name="src">
				<xsl:value-of select="$slika" disable-output-escaping="no" />
			</xsl:attribute>
		</img>
		</a>
    </xsl:template>
</xsl:stylesheet>