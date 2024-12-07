<!-- BEGIN: main -->
<div class="user-info">
    <div class="user-info-list">
        <ul class="nv-list-item">
            <li><a href="/users/" title=""><i class="fa fa-user" aria-hidden="true"></i> Trang tài khoản</a></li>
            <li class="active"><a href="/order-list/" title=""><i class="fa fa-cart-plus" aria-hidden="true"></i> Đơn hàng</a></li>
            <li><a href="/users/editinfo/basic/" title=""><i class="fa fa-cog" aria-hidden="true"></i> Thiết lập tài khoản</a></li>
            <li><a href="/users/logout/" title=""><i class="fa fa-sign-out" aria-hidden="true"></i> Đăng xuất</a></li>
        </ul>
    </div>
    <div class="user-info-content order-list padding-40">
        <table>
        	<thead>
        		<tr>
        			<th>Đơn hàng</th>
        			<th>Ngày đặt hàng</th>
        			<th>Tình trạng</th>
        			<th>Tổng</th>
        			<th></th>
        		</tr>
        	</thead>
        	<tbody>
        		<!-- BEGIN: loop -->
        		<tr>
        			<td><a href="{DATA.link}" title="">{DATA.order_code}</a></td>
        			<td>{DATA.order_time}</td>
        			<td>{DATA.status}</td>
        			<td>{DATA.price}đ</td>
        			<td><a class="btn" href="{DATA.link}" title="">Xem</a></td>
        		</tr>
        		<!-- END: loop -->
        	</tbody>
        </table>
    </div>
</div>
<!-- END: main -->