import React from 'react';
import Autosuggest from 'react-autosuggest';
import Fetch from '../fetch';

/* --------------- */
/*    Component    */
/* --------------- */

function getSuggestionValue(suggestion) {
	return suggestion.id;
}

function renderSuggestion(suggestion) {
	return (
		<span>{suggestion.title}</span>
	);
}

function shouldRenderSuggestions(value) {
	return 'string' === typeof value && value.trim().length > 3;
}

class Input extends React.Component {
	constructor() {
		super();

		this.state = {
			value: '',
			suggestions: [],
			isLoading: false
		};

		this.onChange = this.onChange.bind(this);
		this.onSuggestionsUpdateRequested = this.onSuggestionsUpdateRequested.bind(this);
		this.onSuggestionSelected = this.onSuggestionSelected.bind(this);
	}

	loadSuggestions(value) {
		this.setState({
			isLoading: true
		});

		Fetch( TenUpCuration.base_url, 'curation/content?section=' + JSON.stringify( this.props.section )  + '&search=' + value, {}, {}, { auth: true })
			.then( ( suggestions ) => {
				if (value === this.state.value) {
					this.setState({
						isLoading: false,
						suggestions,
					});
				} else { // Ignore suggestions if input value changed
					this.setState({
						isLoading: false
					});
				}
			})
			.catch( (error) => {
				console.log( error );
				this.setState({
					isLoading: false,
				});
			})
	}

	onChange(event, { newValue }) {
		this.setState({
			value: newValue
		});
	}

	onSuggestionSelected(event, { suggestion, suggestionValue }) {
		this.setState({
			suggestions: [],
			value: '',
		});
		this.props.onAddContent( suggestion );
	}

	onSuggestionsUpdateRequested({ value }) {
		this.loadSuggestions(value);
	}
	render() {
		const { value, suggestions, isLoading } = this.state;
		const inputProps = {
			placeholder: "Search for content to curate",
			value,
			onChange: this.onChange
		};
		const status = (isLoading ? 'Loading...' : '');

		return (
			<div className="curation-input">
				<Autosuggest suggestions={suggestions}
				             onSuggestionsUpdateRequested={this.onSuggestionsUpdateRequested}
				             onSuggestionSelected={this.onSuggestionSelected}
				             getSuggestionValue={getSuggestionValue}
				             renderSuggestion={renderSuggestion}
				             shouldRenderSuggestions={shouldRenderSuggestions}
				             inputProps={inputProps} />
				<div className=".react-autosuggest__status">
					{status}
				</div>
			</div>
		);
	}
}

export default Input;