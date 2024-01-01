import React, { useState, useEffect } from 'react';
import axios from 'axios';
import botImage from './../images/bot.png';
import onlineImage from './../images/online.png';
import offlineImage from './../images/offline.png';
import idleImage from './../images/idle.png';
import dndImage from './../images/dnd.png';

const MemberPopup = ({ memberId, onClose }) => {
    const [member, setMember] = useState(null);

    const fetchData = () => {
        axios.get(`http://localhost:3001/api/member/${memberId}`)
            .then(response => {
                setMember(response.data);
                console.log(response.data);
            })
            .catch(error => {
                console.error("Error fetching member:", error);
            });
    };

    // Fetch data when the component mounts
    useEffect(() => {
        fetchData();
    }, [memberId]);

    const closePopup = () => {
        onClose(); // Assuming onClose is a prop function passed from the parent to handle closing
    };

    const getStatusImage = (status) => {
        switch (status) {
            case 'online':
                return onlineImage;
            case 'idle':
                return idleImage;
            case 'dnd':
                return dndImage;
            default:
                return offlineImage;
        }
    };

    return (
        member && (<div>
            <div className="profilecard">
                <div class="me">
                    <a className="close" onClick={onClose}></a>
                    <div className="avatar">
                        <img className="img-profile" src={member.avatar} alt={member.name} />
                        <img class="img-statusx" src={getStatusImage(member.status)} alt={member.status}></img>
                        {member.bot ? (
                            <img className="img-statusx" style={{ margin: '55px -75px' }} src={botImage} alt="" />
                        ) : (
                            null
                        )}
                    </div>
                    <div className="username">
                        <span>
                            <strong>{member.username}</strong>
                        </span>
                    </div>
                </div>
                <div className="role">
                    <span><strong>ROLES</strong></span>
                    <div className="roles-list">
                        {member.roles.map((role, index) => (
                            <div className="rolex" style={{ display: 'inline-flex' }} key={index}>
                                <div className="color" style={{ backgroundColor: `#${role.color}` }}></div>
                                <div>{role.name}</div>
                            </div>
                        ))}
                    </div>
                </div>
                {member.game && (<div className="note">
                    <div className="noteheader">
                        <span><strong>PLAYING</strong></span>
                        <div style={{ margin: '10px 10px', display: 'flex' }}>
                            {!member.bot && member.gameIcon ?
                                <div class="game_icon">
                                    <img src={member.gameIcon} alt="" />
                                </div> : ""}
                            {(
                                <div style={{ margin: 'auto 5px', fontSize: '12pt' }}>
                                    <span>{member.game.name}</span>
                                </div>
                            )}
                        </div>
                    </div>
                </div>)}
                <div className="tip">
                    <span><strong>Created at: </strong><span id="createDate">{member.creationDate}</span></span><br />
                    <span><strong>Account age: </strong><span id="difDate">{member.accountAge}</span></span><br />
                    <span><strong>Joined at: </strong>{member.joinedDate}</span><br />
                    <span><strong>In server for: </strong>{member.timeInServer}</span>
                </div>
            </div>
        </div>
        )
    );
};

export default MemberPopup;
