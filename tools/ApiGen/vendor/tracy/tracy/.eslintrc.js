module.exports = {
	'env': {
		'browser': true
	},
	'parserOptions': {
		'ecmaVersion': 6
	},
	'globals': {
	},
	'extends': 'eslint:recommended',
	'rules': {
		'indent': ['error', 'tab'],
		'quotes': ['error', 'single'],
		'semi': ['error', 'always'],
		'prefer-arrow-callback': ['error'],
		'arrow-parens': ['error'],
		'arrow-spacing': ['error'],
		'no-var': ['error']
	}
};
