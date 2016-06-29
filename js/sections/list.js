import React from 'react';
import Section from './section';

export default React.createClass({
	render(){
		return (
			<div className="curated-section-list js">
				{
					this.props.sections.map(function (section, index) {
						return (
							<Section section={section} key={section.id} />
						);
					})
				}
			</div>
		);
	}
});