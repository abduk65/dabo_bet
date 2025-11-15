import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
  base: "/",
  plugins: [vue()],
  build: {
    sourceMap: true,
  },
  define:{
    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: true, // Set to true to enable hydration mismatch details
  },
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
});
