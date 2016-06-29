import React from 'react';
import Content from './content';
import Reorder from 'react-reorder';
import Search from './search-input';
import Fetch from '../fetch';

export default React.createClass({

	getInitialState: function() {
		return Object.assign({},{
			showList: false,
		}, this.props.section.state );
	},
	
	toggle: function(event) {
		this.setState({showList: !this.state.showList});
	},

	handleAddContent( addContent ) {

		//already exists
		let exists = this.state.content.filter( function( checkContent ) {
			return JSON.stringify( checkContent ) === JSON.stringify( addContent );
		} );

		if( exists.length ) {
			return;
		}

		let content = [
			...this.state.content,
			addContent
		];
		this.setState({
			content: content,
		}, this.syncToServer );
	},

	handleRemoveContent( removeContent ) {
		let content = [
			...this.state.content
		];
		content.splice( content.indexOf( removeContent ), 1 );
		this.setState({
			content: content,
		}, this.syncToServer );
	},

	syncToServer( fetchOptions = {} ) {
		return Fetch( TenUpCuration.base_url, 'curation/section',
			{
				method: 'post',
			},
			{
				section: this.props.section,
				state: this.state,
			},
			Object.assign( fetchOptions, { auth: true } ),
		)
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
			}).catch(function(error) {
				console.log('Request failed', error);
			});
	},

	itemClicked( event, itemClicked, itemIndex ) {
		if(event.target.classList.contains( 'remove' ) ) {
			this.handleRemoveContent( itemClicked );
		}
	},

	hasReachedContentLimit() {
		return this.state.content.length >= this.props.section.num_content;
	},

	render() {
		let listStyle = {
			display: this.state.showList ? 'block' : 'none',
		};

		let classModifier = ( this.state.showList ? ' open' : ' closed' );
		let wrapperClass = "curated-section postbox" + classModifier;
		let titleClass = 'curated-section-title' + classModifier;

		let searchStyle = {
			display: ( this.hasReachedContentLimit() ? 'none' : 'block' ),
		};

		return (
			<div className={wrapperClass}>

				<div className="curated-section-title-wrap">

					<h2 className={titleClass} title="Click to curate" onClick={this.toggle}>{this.props.section.title}</h2>

					<button type="button" className="handlediv button-link" aria-expanded={this.state.showList} onClick={this.toggle}>
						<span className="screen-reader-text">Toggle panel: {this.props.section.title}</span>
						<span className="toggle-indicator" aria-hidden={!this.state.showList} />
					</button>

				</div>

				<div className="curated-section-elements" style={listStyle}>
					<Reorder
						itemClass="curated-content"
						callback={this.syncToServer}
						itemKey="id"
					    holdTime="300"
					    list={this.state.content}
						itemClicked={this.itemClicked}
					    template={Content}
					/>
					
					<div style={searchStyle}>
						<Search onAddContent={this.handleAddContent} section={this.props.section} />
					</div>

				</div>

			</div>
		);
	}
});
