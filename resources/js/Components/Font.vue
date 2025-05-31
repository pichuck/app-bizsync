<template>
    <!-- Component ini hanya untuk styling, tidak perlu template -->
  </template>
  
  <script>
  export default {
    name: 'FontComponent'
  }
  </script>
  
  <style>
  /* Import Google Font Geologica */
  @import url('https://fonts.googleapis.com/css2?family=Geologica:opsz,wght@6..72,200..800&display=swap');
  
  /* Global font classes */
  .geologica-font {
    font-family: "Geologica", sans-serif;
    font-optical-sizing: auto;
    font-weight: 400;
    font-style: normal;
    font-variation-settings:
      "slnt" 0,
      "CRSV" 0,
      "SHRP" 0;
  }
  
  /* Khusus untuk login page */
  .login-page {
    font-family: "Geologica", sans-serif;
    font-optical-sizing: auto;
    font-weight: 400;
    font-style: normal;
  }
  
  /* Variasi font weight */
  .geologica-light {
    font-family: "Geologica", sans-serif;
    font-weight: 300;
  }
  
  .geologica-medium {
    font-family: "Geologica", sans-serif;
    font-weight: 500;
  }
  
  .geologica-bold {
    font-family: "Geologica", sans-serif;
    font-weight: 700;
  }
  </style>