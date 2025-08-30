const path = require('path');

module.exports = {
  root: true,
  parser: '@typescript-eslint/parser',
  parserOptions: {
    tsconfigRootDir: __dirname,
    project: ['./tsconfig.json'],
    extraFileExtensions: ['.vue'],
  },
  plugins: ['@typescript-eslint', 'vue', 'vitest', 'playwright', 'oxlint'],
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:vue/vue3-recommended',
    'plugin:vitest/recommended',
    'plugin:playwright/recommended',
  ],
  ignorePatterns: ['**/dist/**', '**/dist-ssr/**', '**/coverage/**'],
  rules: {
    // Здесь можно добавить свои правила
  },
};
