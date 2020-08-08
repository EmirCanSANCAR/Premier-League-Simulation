import React from "react";

function Spinner() {
    return (
        <div
            style={{
                position: "fixed",
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
                display: "flex",
                alignItems: "center",
                justifyContent: "center"
            }}
        >
            <div
                style={{
                    position: "absolute",
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0,
                    backgroundColor: "black",
                    opacity: 0.4,
                    zIndex: 1
                }}
            ></div>
            <div
                className="spinner-border"
                style={{ zIndex: 2, color: "white" }}
                role="status"
            >
                <span className="sr-only">Loading...</span>
            </div>
        </div>
    );
}

export default Spinner;
