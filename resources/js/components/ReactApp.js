import React, { useState, useEffect, Suspense } from "react";
import ReactDOM from "react-dom";
import Spinner from "./Spinner";

function ReactApp() {
    const [loading, setLoading] = useState(true);

    const [standings, setStandings] = useState([]);
    const [results, setResults] = useState();

    const playAllHandle = () => {
        setLoading(true);
        fetch("/api/play-all", { method: "POST" })
            .then(response => response.json())
            .then(() => {
                getStanding();
                getResults();
            })
            .catch(() => setLoading(false));
    };

    const nextWeekHandle = () => {
        setLoading(true);
        fetch("/api/next-week", { method: "POST" })
            .then(response => response.json())
            .then(() => {
                getStanding();
                getResults();
            })
            .catch(() => setLoading(false));
    };

    const getStanding = (week = "latest") => {
        setLoading(true);
        fetch("/api/standings")
            .then(response => response.json())
            .then(data => {
                setStandings(data);
            })
            .then(() => setLoading(false))
            .catch(() => setLoading(false));
    };

    const getResults = (week = "latest") => {
        setLoading(true);
        fetch(`/api/results?week=${week}`)
            .then(response => response.json())
            .then(data => {
                setResults(data);
            })
            .then(() => setLoading(false))
            .catch(() => setLoading(false));
    };

    const resetLeagueHandle = () => {
        setLoading(true);
        fetch("/api/reset-league")
            .then(() => {
                getStanding();
                getResults();
                setLoading(false);
            })
            .catch(() => setLoading(false));
    };

    useEffect(() => {
        getStanding();

        getResults();
    }, []);

    useEffect(() => {
        console.log(standings);
    }, [standings]);

    return (
        <>
            <div className="container">
                <div className="row">
                    <div className="col-8">
                        <div className="d-flex">
                            <div className="card w-100">
                                <table className="table table-sm table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th colSpan="7" scope="colgroup">
                                                League Table
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Club</th>
                                            <th scope="col">PTS</th>
                                            <th scope="col">P</th>
                                            <th scope="col">W</th>
                                            <th scope="col">D</th>
                                            <th scope="col">L</th>
                                            <th scope="col">GD</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {standings.map(standing => (
                                            <tr key={standing.football_club_id}>
                                                <td>
                                                    {
                                                        standing.football_club
                                                            .name
                                                    }
                                                </td>
                                                <td>{standing.pts}</td>
                                                <td>{standing.p}</td>
                                                <td>{standing.w}</td>
                                                <td>{standing.d}</td>
                                                <td>{standing.l}</td>
                                                <td>{standing.gd}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>

                            <div className="card w-100">
                                <table className="table table-sm table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th colSpan="3" scope="colgroup">
                                                Match Results
                                            </th>
                                        </tr>
                                        {results && (
                                            <tr>
                                                <th
                                                    colSpan="3"
                                                    scope="colgroup"
                                                >
                                                    <select
                                                        value={
                                                            results.results[0]
                                                                .week
                                                        }
                                                        onChange={e =>
                                                            getResults(
                                                                e.target.value
                                                            )
                                                        }
                                                    >
                                                        {results &&
                                                            new Array(
                                                                results.week_count
                                                            )
                                                                .fill(null)
                                                                .map((v, i) => (
                                                                    <option
                                                                        key={i}
                                                                        value={
                                                                            i +
                                                                            1
                                                                        }
                                                                    >
                                                                        {i + 1}
                                                                    </option>
                                                                ))}
                                                    </select>
                                                    {/* {results.results[0].week} */}
                                                    Week Match Results
                                                </th>
                                            </tr>
                                        )}
                                    </thead>
                                    <tbody>
                                        {results &&
                                            results.results.map(x => (
                                                <tr
                                                    key={
                                                        x.home_football_club_id
                                                    }
                                                >
                                                    <td
                                                        style={{
                                                            textAlign: "left"
                                                        }}
                                                    >
                                                        {
                                                            x.home_football_club
                                                                .name
                                                        }
                                                    </td>
                                                    <td>
                                                        {
                                                            x.home_football_club_goal_count
                                                        }{" "}
                                                        -{" "}
                                                        {
                                                            x.away_football_club_goal_count
                                                        }
                                                    </td>
                                                    <td
                                                        style={{
                                                            textAlign: "right"
                                                        }}
                                                    >
                                                        {
                                                            x.away_football_club
                                                                .name
                                                        }
                                                    </td>
                                                </tr>
                                            ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div
                            className="card flex-row w-100 px-3 py-2"
                            style={{ justifyContent: "space-between" }}
                        >
                            <button
                                type="button"
                                className="btn btn-sm btn-outline-secondary"
                                onClick={playAllHandle}
                            >
                                Play All
                            </button>
                            <button
                                type="button"
                                className="btn btn-sm btn-outline-danger"
                                onClick={resetLeagueHandle}
                            >
                                Reset League
                            </button>
                            <button
                                type="button"
                                className="btn btn-sm btn-outline-secondary"
                                onClick={nextWeekHandle}
                            >
                                Next Week
                            </button>
                        </div>
                    </div>
                    <div className="card col-4">
                        <table className="table table-sm table-striped m-0">
                            <thead>
                                <tr>
                                    <th colSpan="2" scope="colgroup">
                                        {results && results.latest_week}. Week
                                        Predictions of Championship
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {results &&
                                    results.championship_prediction.map(x => (
                                        <tr>
                                            <td>{x.name}</td>
                                            <td>% ∞</td>
                                        </tr>
                                    ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {loading && <Spinner />}
        </>
    );
}

export default ReactApp;

if (document.getElementById("reactApp")) {
    ReactDOM.render(<ReactApp />, document.getElementById("reactApp"));
}
