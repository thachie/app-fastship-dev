<body style="padding: 0;margin: 0;"><div>
    <object id="pdfDocument" data="<?php echo $label; ?>" type="application/pdf" width="100%" height="100%">
        alt : <a href="<?php echo $label; ?>"><?php echo $label; ?></a>
    </object>
</div>
<script>
function printDocument(documentId) {
    var doc = document.getElementById(documentId);

    //Wait until PDF is ready to print    
    if (typeof doc.print === 'undefined') {    
        setTimeout(function(){printDocument(documentId);}, 1000);
    } else {
        doc.print();
    }
}

printDocument("pdfDocument");

</script>
</body>