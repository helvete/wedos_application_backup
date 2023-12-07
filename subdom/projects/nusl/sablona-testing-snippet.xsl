<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:xhtml="http://www.w3.org/1999/xhtml">

<!--xsl:output method="xml" encoding="Windows-1250" /-->

<xsl:template match="/">
<houba> houba </houba>

<xsl:for-each select="/ListRecords/record/header">

<xsl:value-of select="identifier"/>

</xsl:for-each>

</xsl:template>
</xsl:stylesheet>