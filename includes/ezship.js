/* -----------------------------------------------------------------------------------------
   $Id: ezship.js,v 1.1 2003/09/06 22:13:53 oldpa   Exp $  
   ---------------------------------------------------------------------------------------*/
function door() {
      document.all.st_code.style.visibility='hidden';
      document.all.st_code_title.style.visibility='hidden';
      document.all.rv_addr.style.visibility='visible';
      document.all.rv_addr_title.style.visibility='visible';
      document.all.rv_zip.style.visibility='visible';
      document.all.rv_zip_title.style.visibility='visible';
    }

function shop() {
      document.all.st_code.style.visibility='visible';
      document.all.st_code_title.style.visibility='visible';
      document.all.rv_addr.style.visibility='hidden';
      document.all.rv_addr_title.style.visibility='hidden';
      document.all.rv_zip.style.visibility='hidden';
      document.all.rv_zip_title.style.visibility='hidden';
    }