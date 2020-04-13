# Translations

If you wanna add a translation for a foreign language :

1. Add `default.po` and `core.po` files into `src/Locale/xx_XX/`
2. Add a corresponding test case into `src/Table/UsersTable.php@getLocaleByCountryID()` for the locale you added

So as to extract the strings from the source code, and edit them with _Poedit_, just run this command :

`$ bin/cake i18n extract --paths ./src --output ./src/Locale --extract-core yes --merge no --overwrite`

Output files will be under `src/Locale/`, as : `{cake,default}.pot`
