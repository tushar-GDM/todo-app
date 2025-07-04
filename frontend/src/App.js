import { AuthProvider } from "./context/AuthContext";

function App() {
  return (
    <AuthProvider>
      <Routes>...</Routes>
    </AuthProvider>
  );
}
