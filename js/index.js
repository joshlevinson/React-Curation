import React from 'react';
import ReactDOM from 'react-dom';
import SectionList from './sections/list';

var App = React.createClass({
  render: function() {
    return (
      <SectionList sections={TenUpCuration.sections} />
    );
  }
});

ReactDOM.render(<App />, document.getElementById('curated-sections') );