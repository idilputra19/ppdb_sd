import React from 'react';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';

const DashboardCharts = ({ pendaftaranHarian, statusVerifikasi, rataRataNilai }) => {
    const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042'];

    return (
        <div className="row">
            {/* Grafik Pendaftaran Harian */}
            <div className="col-md-8">
                <div className="card">
                    <div className="card-header">
                        <h3 className="card-title">Grafik Pendaftaran 7 Hari Terakhir</h3>
                    </div>
                    <div className="card-body">
                        <div style={{ width: '100%', height: 300 }}>
                            <ResponsiveContainer>
                                <LineChart data={pendaftaranHarian}>
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis dataKey="tanggal" />
                                    <YAxis />
                                    <Tooltip />
                                    <Legend />
                                    <Line type="monotone" dataKey="total" stroke="#8884d8" name="Jumlah Pendaftar" />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </div>

            {/* Pie Chart Status Verifikasi */}
            <div className="col-md-4">
                <div className="card">
                    <div className="card-header">
                        <h3 className="card-title">Status Verifikasi</h3>
                    </div>
                    <div className="card-body">
                        <div style={{ width: '100%', height: 300 }}>
                            <ResponsiveContainer>
                                <PieChart>
                                    <Pie
                                        data={statusVerifikasi}
                                        cx="50%"
                                        cy="50%"
                                        labelLine={false}
                                        outerRadius={80}
                                        fill="#8884d8"
                                        dataKey="value"
                                        label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                                    >
                                        {statusVerifikasi.map((entry, index) => (
                                            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                                        ))}
                                    </Pie>
                                    <Tooltip />
                                </PieChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </div>

            {/* Rata-rata Nilai */}
            <div className="col-md-12">
                <div className="card">
                    <div className="card-header">
                        <h3 className="card-title">Rata-rata Nilai Seleksi</h3>
                    </div>
                    <div className="card-body">
                        <div className="row">
                            <div className="col-md-6">
                                <div className="info-box bg-info">
                                    <span className="info-box-icon">
                                        <i className="fas fa-file-alt"></i>
                                    </span>
                                    <div className="info-box-content">
                                        <span className="info-box-text">Rata-rata Nilai Ujian</span>
                                        <span className="info-box-number">
                                            {rataRataNilai.rata_ujian ? rataRataNilai.rata_ujian.toFixed(2) : '-'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-6">
                                <div className="info-box bg-success">
                                    <span className="info-box-icon">
                                        <i className="fas fa-comments"></i>
                                    </span>
                                    <div className="info-box-content">
                                        <span className="info-box-text">Rata-rata Nilai Wawancara</span>
                                        <span className="info-box-number">
                                            {rataRataNilai.rata_wawancara ? rataRataNilai.rata_wawancara.toFixed(2) : '-'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DashboardCharts;