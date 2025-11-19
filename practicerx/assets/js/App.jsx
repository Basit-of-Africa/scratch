import { HashRouter, Routes, Route } from 'react-router-dom';
import Dashboard from './pages/Dashboard';
import Appointments from './pages/Appointments';
import Patients from './pages/Patients';
import Settings from './pages/Settings';
import Layout from './components/Layout';

const App = () => {
    return (
        <HashRouter>
            <Layout>
                <Routes>
                    <Route path="/" element={<Dashboard />} />
                    <Route path="/appointments" element={<Appointments />} />
                    <Route path="/patients" element={<Patients />} />
                    <Route path="/settings" element={<Settings />} />
                </Routes>
            </Layout>
        </HashRouter>
    );
};

export default App;
