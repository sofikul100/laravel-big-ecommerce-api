import React from 'react';
import Home from './components/Home';
import  ReactDOM  from 'react-dom';




if(document.getElementById('app')){
    ReactDOM.render(<Home/>,document.getElementById('app'));
}