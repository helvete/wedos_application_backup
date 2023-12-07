<xsl:stylesheet xmlns:xsl='http://www.w3.org/1999/XSL/Transform' version='1.0'> 

<xsl:template match='/ListRecords'>

<xsl:apply-templates select="record" /> 

</xsl:template>   

<xsl:template match='record'> 
<xsl:copy-of select='metadata' />
</xsl:template> 

</xsl:stylesheet>