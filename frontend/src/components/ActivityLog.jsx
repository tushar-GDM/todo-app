import { useState, useEffect, useContext } from "react";
import axios from "axios";
import { AuthContext } from "../context/AuthContext";

function ActivityLog() {
  const { token } = useContext(AuthContext);
  const [logs, setLogs] = useState([]);

  const fetchLogs = async () => {
    try {
      const res = await axios.get(`${process.env.REACT_APP_API_URL}/log.php`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      setLogs(res.data);
    } catch (err) {
      console.error("Failed to load activity logs", err);
    }
  };

  useEffect(() => {
    fetchLogs();
  }, []);

  return (
    <div style={{ marginTop: "30px", padding: "10px", border: "1px solid #ddd", background: "#fff" }}>
      <h3>🕒 Activity Log (Last 20)</h3>
      <ul>
        {logs.map((log, idx) => (
          <li key={idx}>
            <strong>{log.user_name}</strong> → {log.action} @ {new Date(log.timestamp).toLocaleString()}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ActivityLog;
