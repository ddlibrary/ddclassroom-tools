<template>
    <div class="container">
      <h1>Enable Two-Factor Authentication</h1>

      <p>Scan this QR code with your authenticator app to enable two-factor authentication.</p>
{{ qrCode }}
      <div v-html="qrCode"></div>

      <p>Keep your recovery codes safe:</p>
      <ul>
        <li v-for="(code, index) in recoveryCodes" :key="index">{{ code }}</li>
      </ul>
    </div>
  </template>

  <script>
  export default {
    props: {
      qrCode: String,
      recoveryCodes: Array,
    },
  };
  </script>

  <style scoped>
  .container {
    max-width: 600px;
    margin: 0 auto;
  }
  </style>
