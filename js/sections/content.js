import React from 'react';

export default React.createClass({
	render() {
		return (
			<div className="inner">
				<h4 title="Drag to reorder" className="curated-content-title">{this.props.item.title}</h4>
				<span className="remove dashicons dashicons-trash"></span>
			</div>
		);
	}
});