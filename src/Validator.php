<?php
namespace LibWeb;

/**
 * Validator main class
 */
class Validator {

	/// Validate a value against a rule
	public static function validate( $value, $rule ) {
		$state = new validator\State( $value );
		self::validateState( $state, self::required() );
		if ( $state->getErrors() )
			throw new ValidatorException( $state );
		self::validateState( $state, $rule );
		if ( $state->getErrors() )
			throw new ValidatorException( $state );
		return $state->value;
	}
	/// Validate the state object
	public static function validateState( $state, $rule ) {
		$rule = self::normalizeRule( $rule );
		$rule->setup( $state );
		$rule->apply( $state );
	}
	/// Normalize a rule
	public static function normalizeRule( $rule ) {
		if ( is_array( $rule ) )
			$rule = new validator\rule\RuleObject( $rule );
		else if ( !$rule instanceof validator\Rule )
			throw new \InvalidArgumentException( "Rule must be an array or an instance of Rule" );
		return $rule;
	}
	/**
	 * Call a static rule on validatr
	 */
	public static function __callStatic( $name, $args ) {
		$builder = new validator\rule\RuleBuilder;
		return $builder->__call( $name, $args );
	}
	
};