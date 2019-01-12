import React, {Component} from 'react';
import ReactDOM from 'react-dom';
// Layout components
import Header from './components/MainLayout/Header';
import SideBar from './components/MainLayout/SideBar';
import Content from './components/MainLayout/Content'

class App extends Component {

    render() {
        return (
            <div>
                <Header/>
                <SideBar/>
                <Content/>
            </div>
        );
    }
}

export default App;

// Render the app
ReactDOM.render(<App/>, document.getElementById('avalonApp'));
